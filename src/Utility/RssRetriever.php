<?php
/**
 * Created by PhpStorm.
 * User: Sofia Johansson
 * Date: 2017-11-09
 * Time: 13:44
 */

namespace App\Utility;

use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

/*
 *  Class that gets and saves RSS-feeds
 *
 */
class RssRetriever
{
    private $posts;
    private $channels;

    /*
     *   Private function that validates the provided url
     *   Input: users url
     *   Return: boolean
     */
    public function checkUrl($url)
    {
        if(filter_var($url, FILTER_VALIDATE_URL) == FALSE) {
            if(   strpos(@get_headers($url)[0],'200') == FALSE) {
                if(strpos(file_get_contents($url), '<xml') == FALSE) {
                    return false;
                }
            }
        }
        return true;
    }

    /*
     *   Input: channel or null (null returns posts for all saved channels)
     *   Return: rss-posts for one or all channels
     */
    public function getRssFeeds($channel = null)
    {
        $this->posts = TableRegistry::get('Posts');
        $rssPosts = null;
        if($channel != null) {
            $this->saveFeed($channel);
            return $this->posts->find('all')
                ->where(['posts.channel_id' => $channel->id]);
        }
        else {
            $this->channels = TableRegistry::get('Channels');
            $channels = $this->channels->find('all');
            foreach ($channels as $channel) {
                $this->saveFeed($channel);
            }
            return $this->posts->find('all')
                ->contain(['Channels']);
        }
    }

    /*      Input: Channel
     *      Returns: None
     *      Private function that gets a rss-feed and saves it to db.
     */
    private function saveFeed($channel)
    {
        $feed = simplexml_load_file($channel->channel_url);
        /*
         * Here should be added some validation, if the feed is empty, that gives a message that the url is unavailable
         */

        $this->posts = TableRegistry::get('Posts');
        foreach ($feed->channel->item as $item) {
            $newPost = $this->posts->newEntity();
            $newPost->channel = $channel;
            $newPost->title = $item->title;
            $newPost->description = $item->description;
            $newPost->link = $item->link;
            $newPost->published = strftime("%Y-%m-%d %H:%M:%S", strtotime($item->pubDate));
            $this->posts->save($newPost);
        }
    }
}