<?php
return [
    'pageSize' => 20,
    'nav' => [
        [
            'icon'  => 'fas fa-sliders-h',
            'label' => 'Category',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'All category', 'href' => '/admin.cms/category/list'],
                ['label' => 'Create & Update', 'href' => '/admin.cms/category/save'],
            ]
        ],
        [
            'icon'  => 'fas fa-newspaper',
            'label' => 'Posts',
            'href'  => '#',
            'open'  => false,
            'child' => [
                ['label' => 'All posts', 'href' => '/admin.cms/posts/list'],
                ['label' => 'Create & Update', 'href' => '/admin.cms/posts/save'],
            ]
        ],
        [
            'icon'  => 'fas fa-user',
            'label' => 'Users',
            'href'  => '#',
            'open'  => false,
            'child' => [
                ['label' => 'All users', 'href' => '/admin.cms/users/list'],
                ['label' => 'Invite Code', 'href' => '/admin.cms/users/code'],
            ]
        ],
    ],
];
