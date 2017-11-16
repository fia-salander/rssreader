<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Channels'), ['controller' => 'Channels', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Channel'), ['controller' => 'Channels', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List all Posts'), ['controller' => 'Posts', 'action' => 'index']) ?></li>
    </ul>
</nav>