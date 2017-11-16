<?php
namespace App\Shell;

use Cake\Console\Shell;
use App\Utility\RssRetriever;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Channels shell command.
 */
class ChannelsShell extends Shell
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Channels');
        $this->loadModel('Posts');
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

    /**
     * main() method.
     *
     * Returns list of all channels
     */
    public function main()
    {
        if($this->getOutputChoice()) {
            /*
             * Shows first 20 due to paginate
             */
            $this->out(file_get_contents( 'http://localhost:8080/rssreader/channels'));
        }
        else{
            $channels = $this->Channels->find('all');
            $this->out('All channels');
            $data = [];
            array_push($data, ['Id', 'Channel', 'Url']);
            foreach ($channels as $channel)
            {
                array_push($data, [$channel->id, $channel->name, $channel->channel_url]);
            }
            $this->helper('Table')->output($data);
        }
    }

    /*
     * Public function for getting rss-posts without sorting
     * If id is provided posts for that id will be printed,
     * otherwise all rss-feeds posts will be printed.
     */
    public function addFeed($feedUrl = 'abc')
    {
        $this->Channels = TableRegistry::get('Channels');

        if ($feedUrl != 'abc') {
            $channel = $this->Channels->newEntity();
            $rssRetriever = new rssRetriever();
            if(!$rssRetriever->checkUrl($feedUrl)) {
                $this->out('The provided url is not a rss-feed. Please, try again.');
            }
            else {
                $channel = $this->Channels->patchEntity($channel, [
                    'name' => $this->in('How do you want to name the feed?'),
                    'channel_url' => $feedUrl]
                );
                if ($this->Channels->save($channel))
                {
                    $this->out('The channel has been saved');
                    $this->posts($channel->id);
                }
                else {
                    $errors = $channel->getErrors();
                    foreach($errors as $error)
                    {
                        $this->out($error);
                    }
                }
            }
        }
        else{
            $this->out('You need to provide a valid rss feed.');
        }
    }

    /*
     * Public function for getting rss-posts for one or all rss-feeds with sorting.
     * Args in: channel nr or default 'all', 'title' or default 'published', 'ASC' or default 'DESC'.
     */
    public function posts($channelId = 'all')
    {
        $sortColumn = $this->getSortColumn();
        $sortOrder = $this->getSortOrder();
        $channel = $this->getChannel($channelId);
        $posts = $this->getRss($channel);
        $output = $this->getOutputChoice();
        if($output == 't') {
            $this->printText($posts, $channel, $sortColumn, $sortOrder);
        }
        else{
            $this->printHTML($posts, $channel, $sortColumn, $sortOrder);
        }
    }

    /*
     * Private function for getting the the users choice of column to sort
     * Returns: t/p
     */
    private function getSortColumn()
    {
        $sortColumn = $this->in('Sorted by Title or Published?', ['T', 'P'], 'P');
        return strtolower($sortColumn);
    }

    /*
     * Private function for getting the the users choice of column to sort
     * Returns: a/d
     */
    private function getSortOrder()
    {
        $sortOrder = $this->in('Sorted Ascending or Descending?', ['A', 'D'], 'D');
        return strtolower($sortOrder);
    }

    /*
     * Private function for getting the the users choice of output
     * Returns: h/t
     */
    private function getOutputChoice()
    {
        $outputChoice =  $this->in('Presented as html or text?', ['H', 'T'], 'T');
        return strtolower($outputChoice);
    }

    /*
     * Private function for getting the channel from id
     * Args in: id
     * Returns: channel
     */
    private function getChannel($channelId)
    {
        $channel = null;
        if($channelId != 'all') {
            try
            {
                $channel = $this->Channels->get($channelId);
            }
            catch(RecordNotFoundException $e) {
                $this->abort('There are no channel number ' . $channelId);
            }
        }
        else{
            $channel = null;
        }
        return $channel;
    }

    /*
     * Private function for getting the required result
     * Args in: channel
     * Returns: posts
     */
    private function getRss($channel)
    {
        $rssRetriever = new RssRetriever();
        $posts = $rssRetriever->getRssFeeds($channel);

        return $posts;
    }

    /*
     * Private function for printing text result to console
     * Args in: posts, channel, sortColumn and sortOrder
     * Out: result
     */
    private function printText($posts, $channel, $sortColumn, $sortOrder)
    {
        if(empty($posts))
        {
            if($channel == null) {
                $text = "There are no posts.";
            }
            else {
                $text = $channel->title . ' has no posts.';
            }
            $this->out($text);
        }
        else {
            $posts = $this->sortFeed($posts, $sortColumn, $sortOrder);
            $data = [];
            /*
             * Adds different heading and table heading due to if it is all or one channel
             */
            if($channel != null){
                $this->out($channel->name);
                array_push($data, ['Title','Published']);
            }
            else{
                $this->out('All posts');
                array_push($data, ['Title','Published', 'Channel']);
            }

            foreach ($posts as $post)
            {
                /*
             * Adds different content due to if it is all or one channel
             */
                if($channel != null) {
                    array_push($data, [$post->title, $post->published->i18nFormat('yyyy-MM-dd HH:mm:ss')]);
                }
                else{
                    array_push($data, [$post->title, $post->published->i18nFormat('yyyy-MM-dd HH:mm:ss'), $post->channel->name]);
                }
            }
            $this->helper('Table')->output($data);
        }
    }

    /*
    * Private function for printing HTML result to console
    * Shows first 20 due to paginate
    * Args in: posts, channel, sortColumn and sortOrder
    * Out: result
    */
    private function printHTML($posts, $channel, $sortColumn, $sortOrder)
    {
        /*
         * All channels
         */
        if($channel != null) {
            $url = 'http://localhost:8080/rssreader/channels/view/' . $channel->id;
            if ($sortColumn === 't') {
                if ($sortOrder === 'a') {
                    $url .= '?sort=title&direction=asc';
                } else {
                    $url .= '?sort=title&direction=desc';
                }
            } else if ($sortColumn === "p") {
                if ($sortOrder === 'a') {
                    $url .= '?sort=published&direction=asc';
                } else {
                    $url .= '?sort=published&direction=desc';
                }
            }
        }
        /*
         * One channel
         */
        else {
            $url = 'http://localhost:8080/rssreader/posts';
            if ($sortColumn === 't') {
                if ($sortOrder === 'a') {
                    $url .= '?sort=title&direction=asc';
                } else {
                    $url .= '?sort=title&direction=desc';
                }
            } else if ($sortColumn === "p") {
                if ($sortOrder === 'a') {
                    $url .= '?sort=published&direction=asc';
                } else {
                    $url .= '?sort=published&direction=desc';
                }
            }
        }
        $this->out(file_get_contents($url));
    }

    /*
     * Private function for sorting result
     * Args in: posts, sortColumn, sortOrder
     */
    private function sortFeed($posts, $sortColumn = null, $sortOrder = null)
    {
        if($sortColumn === 't')
        {
            if($sortOrder === 'a')
            {
                $posts = $posts->order(['title' => "ASC"]);
            }
            else
            {
                $posts = $posts->order(['title' => "DESC"]);
            }
        }
        else if($sortColumn === "p")
        {
            if($sortOrder === 'a')
            {
                $posts = $posts->order(['published' => "ASC"]);
            }
            else
            {
                $posts = $posts->order(['published' => "DESC"]);
            }
        }
        return $posts;

    }
}
