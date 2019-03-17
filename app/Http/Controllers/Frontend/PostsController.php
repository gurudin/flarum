<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Posts;
use App\Models\Accounts;
use App\Models\Collect;
use App\Models\Comments;
use App\User;

class PostsController
{
    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, string $alias)
    {
        // Category
        $category = Category::where(['alias'=> $alias])->first();

        // Posts
        $order  = $request->get('order', 'new');
        $filter = $request->get('filter', '');

        $query = Posts::where(['status' => 1, 'fk_category_id' => $category->id])->orderBy('weight', 'desc');
        if ($order == 'new') {
            $query = $query->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('pv', 'desc');
        }
        if ($filter != '') {
            $query = $query->where('title', 'like', '%' . $filter . '%');
        }
        $posts = $query->paginate(config('admin.pageSize'));

        return view('frontend.posts.list', compact(
            'category',
            'posts'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, int $id)
    {
        $isRead   = 0; // All users.

        // Post
        $post = Posts::find($id);
        $read_level = $post->read_level;

        // Category
        $category = Category::find($post->fk_category_id);
        if ($category->read_level != 0) {
            $read_level = $category->read_level;
        }

        // Not login.
        if ($read_level != 0 || $post->coin > 0) {
            if (!Auth::check()) {
                $isRead = 1;
                return view('frontend.posts.detail', compact(
                    'isRead',
                    'blade'
                ));
            }
        }

        // Whether VIP can read.
        if (in_array($read_level, [2, 3])) {
            if ((Auth::user()->vip_start_at == null || Auth::user()->vip_end_at == null)
                || strtotime(Auth::user()->vip_end_at) < time()
            ) {
                $isRead = 2;
                return view('frontend.posts.detail', compact(
                    'isRead',
                    'blade'
                ));
            }
        }

        // Check conis.
        if (in_array($read_level, [1, 3]) && $post->coin > 0) {
            if (Auth::user()->coins < $post->coin) {
                $isRead = 3;
                return view('frontend.posts.detail', compact(
                    'isRead',
                    'blade'
                ));
            } else {
                // Check buy post.
                if (Accounts::where([
                    'sort' => Accounts::EXPEND,
                    'fk_posts_id' => $id,
                    'fk_user_id' => Auth::user()->id
                ])->count() == 0) {
                    // To deduct coin.
                    $user = User::find(Auth::user()->id);
                    $user->coins = $user->coins - $post->coin;
                    if ($user->save()) {
                        Accounts::add([
                            'user_id' => Auth::user()->id,
                            'sort'    => Accounts::EXPEND,
                            'type'    => Accounts::POSTS,
                            'coin'    => $post->coin - ($post->coin * 2),
                            'balance' => $user->coins,
                            'post_id' => $id
                        ]);

                        Auth::setUser($user);
                        dd(Auth::user());
                    } else {
                        $isRead = -1;
                        return view('frontend.posts.detail', compact(
                            'isRead',
                            'blade'
                        ));
                    }
                }
            }
        }

        // User
        $from = User::find($post->fk_user_id, ['name as nickname', 'avatar']);

        // Comments
        $comments = Comments::getAllByPosts($post->id);
        
        return view('frontend.posts.detail', compact(
            'isRead',
            'post',
            'category',
            'from',
            'comments'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxCollect(Request $request)
    {
        if (!Auth::check()) {
            return ['status' => true, 'code' => -1, 'msg' => __('frontend.first_login')];
        }

        if (Collect::where(['fk_user_id' => Auth::id(), 'fk_posts_id' => $request->post('pid')])->exists()) {
            return ['status' => true, 'code' => -2, 'msg' => __('frontend.collected')];
        }

        $m = new Collect;
        $m->fk_user_id = Auth::id();
        $m->fk_posts_id = $request->post('pid');
        $m->created_at = date('Y-m-d H:i:s', time());

        return $m->save()
            ? ['status' => true, 'code' => 0, 'msg' => __('frontend.posts.collect_success')]
            : ['status' => false, 'msg' => __('frontend.posts.collect_error')];
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxComment(Request $request)
    {
        if (!Auth::check()) {
            return ['status' => true, 'code' => -1, 'msg' => __('frontend.first_login')];
        }

        if (Comments::where(['fk_user_id' => Auth::id(), 'fk_posts_id' => $request->post('pid')])->count() > 5) {
            return ['status' => false, 'msg' => __('frontend.posts.comments_too_much')];
        }

        if (mb_strlen($request->post('comment')) < 5) {
            return ['status' => false, 'msg' => __('frontend.posts.comment_are_too_short')];
        }
        if (mb_strlen($request->post('comment')) > 100) {
            return ['status' => false, 'msg' => __('frontend.posts.comment_is_too_long')];
        }

        $m = new Comments;
        $m->fk_user_id = Auth::id();
        $m->fk_posts_id = $request->post('pid');
        $m->ip = $request->getClientIp();
        $m->content = $request->post('comment');

        return $m->save()
            ? ['status' => true, 'msg' => __('frontend.posts.comment_success')]
            : ['status' => false, 'msg' => __('frontend.posts.comment_error')];
    }
}
