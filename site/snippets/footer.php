<footer class="footer cf" role="contentinfo">

  <div class="copyright">
    <?php echo $site->copyright()->kirbytext() ?>
  </div>

  <div class="colophon">
    <a href="http://getkirby.com/made-with-kirby-and-love"><?php echo l::get('made-with') ?></a>
  </div>


</footer>

<!-- scripts -->
<?php if ( c::get('environment') == 'local' ) : ?>

<?= js('assets/js/plugins.js') ?>
<?= js('assets/js/main.js') ?>

<?php else: ?>

<?= js('assets/production/all.min.js') ?>

<?php endif ?>

</body>
</html>
