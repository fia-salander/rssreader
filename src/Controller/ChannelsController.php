<?php
namespace App\Controller;

use App\Utility\RssRetriever;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\NotImplementedException;

/**
 * Channels Controller
 *
 * @property \App\Model\Table\ChannelsTable $Channels
 *
 * @method \App\Model\Entity\Channel[] paginate($object = null, array $settings = [])
 */
class ChannelsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $channels = $this->paginate($this->Channels);

        $this->set(compact('channels'));
        $this->set('_serialize', ['channels']);
    }

    /**
     * View method
     *
     * @param string|null $id Channel id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $rssRetriever = new RssRetriever();
        try {
            $channel = $this->Channels->get($id);
        }
        catch(RecordNotFoundException $e){
            $this->Flash->error(__('Channel number ' . $id . ' do not exist.'));
            return $this->redirect(['action' => 'index']);
        }
        $posts = $rssRetriever->getRssFeeds($channel);

        $posts = $this->paginate($posts);
        $this->set('channel', $channel);
        $this->set('posts', $posts);
        $this->set('_serialize', ['posts']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $channel = $this->Channels->newEntity();
        if ($this->request->is('post')) {
            $rssRetriever = new rssRetriever();
            if(!$rssRetriever->checkUrl($this->request->getData('channel_url'))) {
                $this->Flash->error(__('The provided url is not a rss-feed. Please, try again.'));
            }
            else {
                $channel = $this->Channels->patchEntity($channel, $this->request->getData());
                if ($this->Channels->save($channel)) {
                    $this->Flash->success(__('The channel has been saved.'));

                    return $this->redirect(['action' => 'view', $channel->id]);
                }

                $this->Flash->error(__('The channel could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('channel'));
        $this->set('_serialize', ['channel']);
}

    /**
     * Edit method - auto-implemented. Not active.
     *
     * @param string|null $id Channel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        throw new NotImplementedException();
    }

    /**
     * Delete method - auto-implemented. Not active.
     *
     * @param string|null $id Channel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        throw new NotImplementedException();
    }
}
