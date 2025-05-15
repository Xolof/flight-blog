<?php

declare(strict_types=1);

namespace app\controllers;

use app\records\CommentRecord;
use flight\Engine;

class CommentController
{
    /** @var Engine */
    protected Engine $app;

    /**
     * Constructor
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    /**
     * Store
     * 
     * @param int $id The post ID
     * 
     * @return void
     */
    public function store(int $id): void
    {
        $postData = $this->app->request()->data;
        $CommentRecord = new CommentRecord($this->app->db());
        $CommentRecord->post_id = $id;
        $CommentRecord->username = $postData->username;
        $CommentRecord->content = $postData->content;
        $CommentRecord->created_at = gmdate('Y-m-d H:i:s');
        $CommentRecord->updated_at = null;
        $CommentRecord->save();
        $this->app->redirect('/blog/' . $id);
    }

    /**
     * Destroy
     * 
     * @param int $id The post ID
     * @param int $comment_id The comment ID
     * 
     * @return void
     */
    public function destroy(int $id, int $comment_id): void
    {
        $CommentRecord = new CommentRecord($this->app->db());
        $CommentRecord->find($comment_id);
        $CommentRecord->delete();
        $this->app->redirect('/blog/' . $id);
    }

}

