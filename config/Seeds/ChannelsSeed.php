<?php
use Migrations\AbstractSeed;

/**
 * Channels seed.
 */
class ChannelsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array(
                'name' => 'Polisen',
                'channel_url' => 'https://polisen.se/Aktuellt/RSS/Lokal-RSS---Nyheter/Lokala-RSS-listor1/Nyheter-RSS---Vastra-Gotaland/?feed=rss',
            ),
            array(
                'name' => 'Expressen',
                'channel_url' => 'https://feeds.expressen.se/gt/',
            ),
            array(
                'name' => 'SVT',
                'channel_url' => 'http://www.svt.se/nyheter/rss.xml',
            ),
        );
        $table = $this->table('channels');
        $table->insert($data)->save();
    }
}
