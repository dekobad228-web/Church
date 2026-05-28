</main>
<?php wp_footer(); ?>
<?php get_template_part("partials/footer") ?>
<?php get_template_part("partials/modals/modal-index") ?>

</body>
<script>
    window.ajaxUrl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
</script>

</html>