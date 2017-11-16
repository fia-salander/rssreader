<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
?>
<div class="posts view large-9 medium-8 columns content">
    <h2><?= h($post->title) ?></h2>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($post->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Link') ?></th>
            <td><?= h($post->link) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Description') ?></th>
            <td><?= h($post->description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Channel') ?></th>
            <td><?= $post->has('channel') ? $this->Html->link($post->channel->name, ['controller' => 'Channels', 'action' => 'view', $post->channel->id]) : '' ?></td>
        </tr>

        <tr>
            <th scope="row"><?= __('Published') ?></th>
            <td><?= h($post->published) ?></td>
        </tr>
    </table>
</div>
