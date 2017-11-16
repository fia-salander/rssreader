<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Channel[]|\Cake\Collection\CollectionInterface $channels
 */
?>
<div class="channels index large-9 medium-8 columns content">
    <h2><?= __('All channels') ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('channel_url') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($channels as $channel): ?>
            <tr>
                <td><?= $this->Html->link($channel->name, ['controller' => 'Channels', 'action' => 'view', $channel->id]) ?></td>
                <td><?= $this->Html->link($channel->channel_url, $channel->channel_url) ?></td>
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
