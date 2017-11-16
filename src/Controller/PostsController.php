<?php
namespace App\Controller;

use App\Utility\RssRetriever;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 *
 * @method \App\Model\Entity\Post[] paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $rssRetriever = new RssRetriever();
        $posts = $rssRetriever->getRssFeeds();

        $posts = $this->paginate($posts);
        $this->set('posts', $posts);
        $this->set('_serialize', ['posts']);
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $post = $this->Posts->get($id, [
            'contain' => ['Channels']
        ]);

        $this->set('post', $post);
        $this->set('_serialize', ['post']);
    }
}
