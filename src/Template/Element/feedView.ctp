<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 */
?>
<div class="posts index large-9 medium-8 columns content">
    <?php if(isset($channel) && $channel->name != null): ?>
        <h2><?= __($channel->name) ?></h2>
    <?php else: ?>
        <h3><?= __('All posts') ?></h3>
    <?php endif; ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('title') ?></th>
            <th scope="col"><?= $this->Paginator->sort('link') ?></th>
            <th scope="col"><?= $this->Paginator->sort('published') ?></th>
            <?php if(!isset($channel) || $channel->name == null): ?>
                <th scope="col"><?= $this->Paginator->sort('channel') ?></th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= $this->Html->link($post->title, ['controller' => 'Posts', 'action' => 'view', $post->id]) ?></td>
                <td><?= $this->Html->link($post->link, $post->link) ?></td>
                <td><?= h($post->published->i18nFormat('yyyy-MM-dd HH:mm:ss')) ?></td>
                <?php if(!isset($channel) || $channel->name == null): ?>
                    <td><?= $this->Html->link($post->channel->name, ['controller' => 'Channels', 'action' => 'view', $post->channel->id]) ?></td>
                <?php endif; ?>
                <td class="actions">
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>

