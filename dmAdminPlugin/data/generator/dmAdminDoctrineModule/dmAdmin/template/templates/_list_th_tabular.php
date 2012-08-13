<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>

[?php ob_start(); ?]

<th class="sf_admin_<?php echo strtolower($field->getType()) ?> sf_admin_list_th_<?php echo $name ?>">
<?php if ($field->isReal()): ?>
  [?php $translatedLabel = __('<?php echo $field->getConfig('label', '', true) ?>', array(), '<?php echo $this->getModule()->getOption('i18n_catalogue')?>'); ?]

[?php echo _tag('p', array('class' => '', 'title' => __('Sort by %field%', array('%field%' => $translatedLabel), 'dm')),  $translatedLabel) ?]

  
<?php else: ?>
  [?php echo __('<?php echo $field->getConfig('label', '', true) ?>', array(), '<?php echo $this->getModule()->getOption('i18n_catalogue')?>') ?]
<?php endif; ?>
</th>

[?php $currentHeader = ob_get_clean(); ?]

<?php echo $this->addCredentialCondition("[?php print \$currentHeader ?]", $field->getConfig()) ?>
<?php endforeach; ?>

<?php if($this->configuration->getValue('list.object_actions', false)):?>
<th class="sf_admin sf_admin_list_th_object_action<?php echo $name ?>">
[?php echo __('Actions', array(), 'dm');?]
</th>
<?php endif;?>