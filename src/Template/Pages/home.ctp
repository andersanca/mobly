<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Color[]|\Cake\Collection\CollectionInterface $colors
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('Posts'), ['controller' => 'Posts','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Comments'), ['controller' => 'Comments', 'action' => 'index']) ?></li>

    </ul>
</nav>
<div class="colors index large-9 medium-8 columns content">
    <!-- <h1><?= __('ITL - PROJETO') ?></h1> -->
    <center><img src="img/logo-mobly.png" width="200px" style="margin-top: 100px" /></center>
</div>
