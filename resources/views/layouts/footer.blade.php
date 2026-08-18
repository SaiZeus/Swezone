<!-- footer start -->
<footer class="footer footer-style-two pt-140 bg_img" data-background="{{ asset('assets/img/bg/footer-bg.jpg') }}">
    <div class="container">
        <div class="xb-footer-wrap ul_li_between align-items-start">
            <div class="xb-footer_widget mt-30">
                <h3 class="xb-widget-title">Quick Links</h3>
                <ul class="xb-list list-unstyled">
                    <li><a href="{{ url('/team') }}">Speakers</a></li>
                    <li><a href="{{ url('/contact') }}">Registration</a></li>
                    <li><a href="{{ url('/about') }}">About SUMMIT</a></li>
                    <li><a href="{{ url('/faq') }}">Support & FAQ</a></li>
                    <li><a href="{{ url('/blog') }}">OUR blog</a></li>
                </ul>
            </div>
            <div class="xb-newsletter mt-30">
                <div class="xb-item--logo">
                    <img src="{{ asset('assets/img/logo/footer-logo02.svg') }}" alt="">
                </div>
                <p class="xb-item--title">Subscribe to our newsletter</p>
                <form class="xb-item--newsletter_form" action="#">
                    <input type="email" name="email" placeholder="Enter your email">
                    <button class="submit_btn" type="submit">Submit</button>
                </form>
            </div>
            <div class="xb-footer_info mt-30">
                <h3 class="xb-widget-title">Get in touch</h3>
                <ul class="xb-contact list-unstyled">
                    <li><img src="{{ asset('assets/img/icon/location-icon03.svg') }}" alt=""> Los Angeles, Las <br> Vegas, Nevada, USA</li>
                    <li>
                        <img src="{{ asset('assets/img/icon/call-icon03.svg') }}" alt="">
                        <a href="tel:+15615557689">+1 561 555 7689</a>
                    </li>
                    <li>
                        <img src="{{ asset('assets/img/icon/sms-icon02.svg') }}" alt="">
                        <a href="mailto:contact@meetco.com">contact@meetco.com</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="xb-footer_bottom">
        <div class="container">
            <div class="xb-footer-bottom-inner ul_li_between">
                <p>Copyright © 2025 <a href="{{ url('/') }}">MEETCO,</a> All rights reserved.</p>
                <div class="xb-social_media">
                    <ul class="social-link list-unstyled ul_li">
                        <li>Follow us :</li>
                        <li><a href="#!"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="#!"><i class="fa-brands fa-linkedin-in"></i></a></li>
                        <li><a href="#!"><i class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->