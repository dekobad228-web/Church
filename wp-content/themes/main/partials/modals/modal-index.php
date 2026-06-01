<?php
$donations = get_field("donations", "option");
?>
<div id="modal-index" class="modal base-modal modal-index" data-micromodal-close="" aria-hidden="true">
    <div class="modal__overlay">
        <div class="container modal__container">
            <div class="modal-index__block">
                <button class="modal-index__close" data-micromodal-close="">
                    <span class="modal__close-line"></span>
                    <span class="modal__close-line"></span>
                </button>
                <div class="modal-index__top">
                    <h2 class="h2-400 modal-index__title">
                        <?= $donations['title'] ?>
                    </h2>
                    <p class="p2-400 modal-index__text">
                        <?= $donations['text']; ?>
                    </p>
                </div>
                <div class="modal-index__bottom">

                    <form class="modal-index__form" action="" method="POST" hx-post="<?= AJAX_PATH ?>" hx-indicator="find .btn-submit">
                        <input type="hidden" name="action" value="donate">
                        <p class="p2-400 modal-index__label">Сумма пожертвования</p>

                        <div class="modal-index__input-box">
                            <input type="number" name="donation_amount" class="modal-index__input" placeholder="руб" id="donation_amount">
                            <label for="donation_amount" class="modal-index__placeholder-label">руб</label>
                        </div>

                        <button type="submit" class="btn btn-submit modal-index__btn">
                            Пожертвовать
                        </button>
                    </form>
                    <?php if ($donations['qr']) : ?>
                        <div class="modal-index__picture">
                            <img src="<?= $donations['qr']['url'] ?>" class="modal-index__image" alt="<?= $donations['qr']['alt'] ?>">
                            <p class="p3-400 modal-index__sign">
                                или отсканируйте QR-код
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>