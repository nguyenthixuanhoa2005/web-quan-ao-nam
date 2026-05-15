<?php
/**
 * The template for displaying the footer.
 * Customized for Bear Shop - Anime Plushie.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Thoát nếu truy cập trực tiếp
}
?>

<?php astra_content_bottom(); ?>
	</div> </div><?php astra_content_after(); ?>

<style>
    .custom-men-footer {
        background-color: #111111; /* Đen huyền bí, sang trọng */
        color: #ffffff;
        padding: 80px 0 40px;
        font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        border-top: 1px solid #333333;
        margin-top: 60px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-men-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 50px;
    }

    .footer-title {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase; /* Chữ in hoa mạnh mẽ */
        letter-spacing: 2px;
        margin-bottom: 30px;
        color: #ffffff;
        position: relative;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 30px;
        height: 2px;
        background-color: #ffffff;
    }

    .footer-column p {
        line-height: 1.8;
        font-size: 14px;
        color: #999999;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 15px;
    }

    .footer-links a {
        color: #999999;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer-links a:hover {
        color: #ffffff;
        padding-left: 5px;
    }

    .contact-info {
        font-style: normal;
        font-size: 14px;
        color: #999999;
    }

    .contact-item {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .copyright-bar {
        text-align: center;
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid #222222;
        font-size: 12px;
        color: #666666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Tối ưu cho điện thoại */
    @media (max-width: 768px) {
        .custom-men-footer {
            padding: 50px 0 30px;
            text-align: center;
        }
        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }
        .footer-links a:hover {
            padding-left: 0;
        }
    }
</style>

<footer id="colophon" class="site-footer custom-men-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-men-grid">
            
            <div class="footer-column">
                <h4 class="footer-title">Top Men 🎩</h4>
                <p>Nơi định hình phong cách phái mạnh hiện đại. Chúng mình cam kết mang đến những bộ trang phục tối giản, lịch lãm và chất lượng vượt trội cho quý ông.</p>
            </div>

            <div class="footer-column">
                <h4 class="footer-title">Hỗ Trợ 💼</h4>
                <ul class="footer-links">
                    <li><a href="/chinh-sach-doi-tra">Chính sách đổi trả</a></li>
                    <li><a href="/huong-dan-chon-size">Hướng dẫn chọn size</a></li>
                    <li><a href="/he-thong-cua-hang">Hệ thống cửa hàng</a></li>
                    <li><a href="/chinh-sach-bao-mat">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4 class="footer-title">Kết Nối 📍</h4>
                <div class="contact-info">
                    <div class="contact-item">🏠 123 Đường Thời Trang, Quận 1, HCM</div>
                    <div class="contact-item">📞 Hotline: 0900.XXX.XXX</div>
                    <div class="contact-item">✉️ Email: contact@topmen.vn</div>
                </div>
            </div>

        </div>

        <div class="copyright-bar">
            &copy; <?php echo date('Y'); ?> Top Men - Essential Menswear. All rights reserved.
        </div>
    </div>
</footer>

<?php
	astra_footer_before();
	// astra_footer(); // Hook mặc định của Astra - Có thể tắt nếu muốn dùng hoàn toàn footer này
	astra_footer_after();
?>

	</div><?php
	astra_body_bottom();
	wp_footer(); // CỰC KỲ QUAN TRỌNG: Không được xóa vì đây là nơi load Plugin, Chatbot...
?>
	</body>
</html>