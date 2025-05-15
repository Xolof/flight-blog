<?php

declare(strict_types=1);

namespace app\controllers;

use app\records\CommentRecord;
use app\records\PostRecord;

class PostController extends BaseController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        $PostRecord = new PostRecord($this->db());
        $posts = $PostRecord->order('id DESC')->findAll();
        $CommentRecord = new CommentRecord($this->db());
        foreach($posts as &$post) {
            $post->comments = $CommentRecord->eq('post_id', $post->id)->findAll();
        }

        $this->render('posts/index.latte', [ 'page_title' => 'Blog', 'posts' => $posts]);
    }

    /**
     * Create
     *
     * @return void
     */
    public function create(): void
    {
        $this->render('posts/create.latte', [ 'page_title' => 'Create Post']);
    }

    /**
     * Store
     *
     * @return void
     */
    public function store(): void
    {
        $postData = $this->request()->data;
        $PostRecord = new PostRecord($this->db());
        $PostRecord->title = $postData->title;
        $PostRecord->content = $postData->content;
        $PostRecord->username = $postData->username;
        $PostRecord->created_at = gmdate('Y-m-d H:i:s');
        $PostRecord->updated_at = null;
        $PostRecord->save();
        $url = $this->app->getUrl('blog');
        $this->app->redirect($url);
    }

    /**
     * Show
     *
     * @param int $id The ID of the post
     * @return void
     */
    public function show(int $id): void
    {
        $PostRecord = new PostRecord($this->db());
        $post = $PostRecord->find($id);
        $CommentRecord = new CommentRecord($this->db());
        $post->comments = $CommentRecord->eq('post_id', $post->id)->findAll();
        $this->render('posts/show.latte', [ 'page_title' => $post->title, 'post' => $post]);
    }

    /**
     * Edit
     *
     * @param int $id The ID of the post
     * @return void
     */
    public function edit(int $id): void
    {
        $PostRecord = new PostRecord($this->db());
        $post = $PostRecord->find($id);
        $this->render('posts/edit.latte', [ 'page_title' => 'Update Post', 'post' => $post]);
    }

    /**
     * Update
     *
     * @param int $id The ID of the post
     * @return void
     */
    public function update(int $id): void
    {
        $url = $this->app->getUrl('blog');
   
        $postData = $this->request()->data;
        $url = $this->app->getUrl('blog');
        $PostRecord = new PostRecord($this->db());
        $post = $PostRecord->find($id);
        if (count($post->getData()) === 0) {
          $this->app->redirect($url);
          return;
        };
        $PostRecord->title = $postData->title;
        $PostRecord->content = $postData->content;
        $PostRecord->username = $postData->username;
        $PostRecord->updated_at = gmdate('Y-m-d H:i:s');
        $PostRecord->save();
        $this->app->redirect($url);
    }

    /**
     * Destroy
     *
     * @param int $id The ID of the post
     * @return void
     */
    public function destroy(int $id): void
    {
        $PostRecord = new PostRecord($this->db());
        $post = $PostRecord->find($id);
        $post->delete();
        $url = $this->app->getUrl('blog');
        $this->app->redirect($url);
    }
}

