<?php

declare(strict_types=1);

namespace app\controllers;

use app\records\CommentRecord;

class CommentController extends BaseController
{
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

        $url = $this->app->getUrl('blog');
        $this->app->redirect($url . "/$id");
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

        $url = $this->app->getUrl('blog');
        $this->app->redirect($url . "/$id");
    }

}

