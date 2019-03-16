<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Posts;
use App\Models\Accounts;
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

        $blade = $request->input('blade', '');
        return view('frontend.posts.list', compact(
            'blade',
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
        $blade  = $request->input('blade', '');
        $isRead = 0; // All users.

        // Post
        $post = Posts::find($id);

        // Not login.
        if ($post->read_level != 0 || $post->coin > 0) {
            if (!Auth::check()) {
                $isRead = 1;
                return view('frontend.posts.detail', compact(
                    'isRead',
                    'blade'
                ));
            }
        }

        // Whether VIP can read.
        if (in_array($post->read_level, [2, 3])) {
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
        if (in_array($post->read_level, [1, 3]) && $post->coin > 0) {
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
        
        // Category
        $category = Category::find($post->fk_category_id);

        // User
        $from = User::find($post->fk_user_id, ['name as nickname', 'avatar']);
        
        return view('frontend.posts.detail', compact(
            'isRead',
            'blade',
            'post',
            'category',
            'from'
        ));
    }
}
