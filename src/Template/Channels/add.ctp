<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Channel $channel
 */
?>
<div class="channels form large-9 medium-8 columns content">
    <?= $this->Form->create($channel) ?>
    <fieldset>
        <legend><?= __('Add Channel') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('channel_url');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
