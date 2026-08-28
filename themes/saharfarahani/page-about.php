<?php
/**
 * About page template — Sahar Farahani.
 * Use a page with slug "about" to activate this layout.
 */
defined( 'ABSPATH' ) || exit;

$page_id = get_queried_object_id();
$hero_image = get_the_post_thumbnail_url( $page_id, 'sf-portrait' );
$about_image = sf_get_mod( 'sf_about_image', 0 );
$about_image_url = $about_image ? wp_get_attachment_image_url( absint( $about_image ), 'large' ) : $hero_image;
$about_title = sf_get_mod( 'sf_about_title', 'من سحر فراهانی هستم.' );
$about_text = sf_get_mod( 'sf_about_text', 'بازیگر سینما و تلویزیون و گوینده؛ با سال‌ها تجربه در مسیر آموزش و اجرای هنرهای نمایشی.' );
$stat_defaults = array(
    array( 'value' => '+۱۰', 'label' => 'سال تجربه' ),
    array( 'value' => '+۳۰۰۰', 'label' => 'هنرجو' ),
    array( 'value' => '+۴۰', 'label' => 'پروژه هنری' ),
    array( 'value' => '+۷', 'label' => 'افتخار و تقدیر' ),
);
$gallery = get_attached_media( 'image', $page_id );
$gallery = is_array( $gallery ) ? array_values( $gallery ) : array();
if ( empty( $gallery ) && $hero_image ) { $gallery = array( get_post( get_post_thumbnail_id( $page_id ) ) ); }

get_header();
?>
<main class="sf-about-page">
    <section class="sf-about-intro">
        <div class="sf-about-intro__bg" <?php if ( $hero_image ) : ?>style="background-image:url('<?php echo esc_url( $hero_image ); ?>')"<?php endif; ?>></div>
        <div class="sf-about-intro__overlay"></div>
        <div class="sf-container sf-about-intro__grid">
            <div class="sf-about-intro__portrait">
                <?php if ( $hero_image ) : ?><img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( get_the_title( $page_id ) ); ?>"><?php endif; ?>
                <span class="sf-film-frame sf-film-frame--one"></span>
            </div>
            <div class="sf-about-intro__copy">
                <span class="sf-about-gold-kicker">درباره من</span>
                <h1><?php echo esc_html( $about_title ); ?></h1>
                <h2>بازیگر سینما، تلویزیون و گوینده</h2>
                <div class="sf-about-intro__text"><?php echo wpautop( wp_kses_post( $about_text ) ); ?></div>
                <span class="sf-signature">سحر فراهانی</span>
            </div>
            <div class="sf-about-intro__mic" aria-hidden="true">
                <div class="sf-mic-icon">◉</div><span></span><b></b>
            </div>
        </div>
    </section>

    <section class="sf-about-stats">
        <div class="sf-container sf-about-stats__grid">
            <?php foreach ( $stat_defaults as $index => $stat ) :
                $value = sf_get_mod( 'sf_stat_' . ( $index + 1 ) . '_value', $stat['value'] );
                $label = sf_get_mod( 'sf_stat_' . ( $index + 1 ) . '_label', $stat['label'] );
            ?>
                <div class="sf-about-stat">
                    <span class="sf-about-stat__icon"><?php echo $index === 0 ? '♧' : ( $index === 1 ? '▣' : ( $index === 2 ? '♙' : '✦' ) ); ?></span>
                    <strong><?php echo esc_html( $value ); ?></strong>
                    <small><?php echo esc_html( $label ); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="sf-about-career">
        <div class="sf-container sf-about-career__grid">
            <div class="sf-about-skills">
                <span class="sf-about-gold-kicker sf-about-gold-kicker--dark">توانمندی‌ها</span>
                <h2>مهارت‌ها</h2>
                <?php
                $skills = array(
                    array( 'بازیگری', 95 ), array( 'گویندگی', 95 ), array( 'اجرا و دوبله', 90 ),
                    array( 'بیان احساس', 95 ), array( 'فن بیان', 92 ),
                );
                foreach ( $skills as $skill ) :
                ?>
                    <div class="sf-skill">
                        <div><span><?php echo esc_html( $skill[0] ); ?></span><b><?php echo esc_html( $skill[1] ); ?>٪</b></div>
                        <span class="sf-skill__bar"><i style="width:<?php echo esc_attr( $skill[1] ); ?>%"></i></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sf-about-timeline">
                <span class="sf-about-gold-kicker">مسیر هنری من</span>
                <h2>از علاقه تا حرفه‌ای شدن</h2>
                <?php
                $timeline = array(
                    array( '۱۳۷۸', 'شروع علاقه به هنر', 'شروع تئاتر و شرکت در کلاس‌های بازیگری' ),
                    array( '۱۳۸۵', 'ورود به تلویزیون', 'بازی در مجموعه‌های تلویزیونی و تجربه جلوی دوربین' ),
                    array( '۱۳۹۰', 'حرفه‌ای شدن در گویندگی', 'شروع گویندگی تبلیغاتی و دوبله' ),
                    array( '۱۳۹۵', 'دوبله و مستند', 'دوبله مستندها و انیمیشن‌ها برای شبکه‌های مختلف' ),
                    array( '۱۴۰۰', 'تجربه‌های متنوع', 'حضور در سینما، تئاتر و پروژه‌های صوتی متنوع' ),
                );
                foreach ( $timeline as $item ) :
                ?>
                    <div class="sf-timeline-item">
                        <strong><?php echo esc_html( $item[0] ); ?></strong>
                        <div><h3><?php echo esc_html( $item[1] ); ?></h3><p><?php echo esc_html( $item[2] ); ?></p></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="sf-about-media">
        <div class="sf-container sf-about-media__grid">
            <div class="sf-voice-demo">
                <div class="sf-about-section-title"><span>نمونه صدای گویندگی</span><h2>صدای من را بشنوید</h2></div>
                <div class="sf-voice-card">
                    <div class="sf-voice-avatar"><?php if ( $hero_image ) : ?><img src="<?php echo esc_url( $hero_image ); ?>" alt="" loading="lazy"><?php endif; ?></div>
                    <div class="sf-voice-info"><strong>گویندگی تیزر تبلیغاتی</strong><small>نمونه کار ۱</small></div>
                    <div class="sf-wave"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    <button type="button" class="sf-voice-play" aria-label="پخش نمونه صدا">▶</button>
                    <span class="sf-voice-time">00:00 / 01:27</span>
                </div>
                <a class="sf-about-outline-button" href="#voice">مشاهده نمونه‌های بیشتر <span>♩</span></a>
            </div>

            <div class="sf-photo-gallery">
                <div class="sf-about-section-title"><span>گالری تصاویر</span><h2>لحظه‌هایی از مسیر من</h2></div>
                <div class="sf-gallery-grid">
                    <?php foreach ( array_slice( $gallery, 0, 4 ) as $attachment ) :
                        if ( ! $attachment ) { continue; }
                        $src = wp_get_attachment_image_url( $attachment->ID, 'medium_large' );
                    ?>
                        <a href="<?php echo esc_url( wp_get_attachment_image_url( $attachment->ID, 'full' ) ); ?>" class="sf-gallery-item"><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ?: get_the_title( $page_id ) ); ?>" loading="lazy"></a>
                    <?php endforeach; ?>
                </div>
                <a class="sf-about-outline-button" href="<?php echo esc_url( get_permalink( $page_id ) ); ?>#gallery">مشاهده گالری کامل <span>▧</span></a>
            </div>
        </div>
    </section>

    <?php if ( trim( get_post_field( 'post_content', $page_id ) ) ) : ?>
    <section class="sf-about-story">
        <div class="sf-container sf-about-story__inner">
            <span class="sf-about-gold-kicker">داستان من</span>
            <h2>بیشتر درباره من</h2>
            <div class="sf-entry-content"><?php echo apply_filters( 'the_content', get_post_field( 'post_content', $page_id ) ); ?></div>
        </div>
    </section>
    <?php endif; ?>

    <section class="sf-about-contact">
        <div class="sf-container sf-about-contact__inner">
            <div class="sf-reel-decoration" aria-hidden="true">◉</div>
            <div><span class="sf-about-gold-kicker">همکاری با من</span><h2>بیایید با هم یک داستان ماندگار بسازیم</h2><p>اگر برای یک پروژه بازیگری، گویندگی یا همکاری هنری به دنبال یک همراه حرفه‌ای هستید، خوشحال می‌شوم در ارتباط باشیم.</p></div>
            <a class="sf-about-gold-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">تماس با من <span>☎</span></a>
        </div>
    </section>
</main>
<?php get_footer(); ?>
