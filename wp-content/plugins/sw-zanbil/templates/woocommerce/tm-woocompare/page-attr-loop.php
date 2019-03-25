<?php if ( !empty( $attrs ) && ( is_array( $attrs ) ) ): ?>
    <?php foreach ( $attrs as $attr_title => $attr_values ): ?>
        <li>
            <div class="c-compare__list-title"><?= $attr_title ?></div>
        </li>
        <li>
            <?php if ( !empty( $attr_values ) && is_array( $attr_values ) ): ?>
                <?php foreach ( $attr_values as $id => $attr_value ): ?>
                    <div class="c-compare__list-value" data-id="<?= $id ?>">
                        <span class="block"><?= $attr_value ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- A hack for fix horizontal scroll-->
            <div class="c-compare__list-value"><span class="block"></span></div>
        </li>
    <?php endforeach; ?>
<?php endif; ?>