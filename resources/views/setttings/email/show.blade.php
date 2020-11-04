@extends('layout.index')
@section('content')
<style>
    .email-body
    {
        color:black;
    }
    .email-content
    {
        /*max-width: 450px;*/
        width : 90%;
    }
    .email-content-detail
    {
        margin: 50px 0;
    }

    .select2-selection__choice{
        background-color: #7daa40 !important;
        border-radius: 0px;
        border: 1px solid #7daa40 !important;
    }
    @media (max-width: 570px) {
        .email_btn
        {
            padding:15px 30px !important;
            font-size:18px !important;
        }
    }
    @media (max-width: 430px) {
        .email_btn {
            padding: 15px 20px !important;
            font-size: 12px !important;
        }
    }
    @media (max-width: 400px) {
        .email_btn {
            padding: 15px 10px !important;
            font-size: 12px !important;
        }
        span
        {
            font-size:18px !important ;
        }
    }

    @font-face {
        font-family:Sofia_Pro_Light;
        src:url(https://app.wefullfill.com/Sofia_Pro_Light.otf);
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin Italic'), local('Montserrat-ThinItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUOjIg1_i6t8kCHKm459WxZqh7p29NNpQ.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin Italic'), local('Montserrat-ThinItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUOjIg1_i6t8kCHKm459WxZqh7g29NNpQ.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin Italic'), local('Montserrat-ThinItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUOjIg1_i6t8kCHKm459WxZqh7r29NNpQ.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin Italic'), local('Montserrat-ThinItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUOjIg1_i6t8kCHKm459WxZqh7q29NNpQ.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin Italic'), local('Montserrat-ThinItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUOjIg1_i6t8kCHKm459WxZqh7k29M.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight Italic'), local('Montserrat-ExtraLightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZBg_z8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight Italic'), local('Montserrat-ExtraLightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZBg_z-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight Italic'), local('Montserrat-ExtraLightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZBg_z8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight Italic'), local('Montserrat-ExtraLightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZBg_z8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight Italic'), local('Montserrat-ExtraLightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZBg_z_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light Italic'), local('Montserrat-LightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZYgzz8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light Italic'), local('Montserrat-LightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZYgzz-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light Italic'), local('Montserrat-LightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZYgzz8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light Italic'), local('Montserrat-LightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZYgzz8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light Italic'), local('Montserrat-LightItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZYgzz_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Italic'), local('Montserrat-Italic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm459WxRxC7mw9c.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Italic'), local('Montserrat-Italic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm459WxRzS7mw9c.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Italic'), local('Montserrat-Italic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm459WxRxi7mw9c.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Italic'), local('Montserrat-Italic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm459WxRxy7mw9c.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Italic'), local('Montserrat-Italic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm459WxRyS7m.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium Italic'), local('Montserrat-MediumItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZOg3z8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium Italic'), local('Montserrat-MediumItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZOg3z-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium Italic'), local('Montserrat-MediumItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZOg3z8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium Italic'), local('Montserrat-MediumItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZOg3z8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium Italic'), local('Montserrat-MediumItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZOg3z_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold Italic'), local('Montserrat-SemiBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZFgrz8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold Italic'), local('Montserrat-SemiBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZFgrz-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold Italic'), local('Montserrat-SemiBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZFgrz8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold Italic'), local('Montserrat-SemiBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZFgrz8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold Italic'), local('Montserrat-SemiBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZFgrz_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold Italic'), local('Montserrat-BoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZcgvz8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold Italic'), local('Montserrat-BoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZcgvz-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold Italic'), local('Montserrat-BoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZcgvz8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold Italic'), local('Montserrat-BoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZcgvz8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold Italic'), local('Montserrat-BoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZcgvz_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold Italic'), local('Montserrat-ExtraBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZbgjz8fZwnCo.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold Italic'), local('Montserrat-ExtraBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZbgjz-PZwnCo.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold Italic'), local('Montserrat-ExtraBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZbgjz8_ZwnCo.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold Italic'), local('Montserrat-ExtraBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZbgjz8vZwnCo.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:italic;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold Italic'), local('Montserrat-ExtraBoldItalic'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUPjIg1_i6t8kCHKm459WxZbgjz_PZw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin'), local('Montserrat-Thin'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm45_QpRxC7mw9c.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin'), local('Montserrat-Thin'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm45_QpRzS7mw9c.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin'), local('Montserrat-Thin'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm45_QpRxi7mw9c.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin'), local('Montserrat-Thin'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm45_QpRxy7mw9c.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:100;
        font-display:swap;
        src:local('Montserrat Thin'), local('Montserrat-Thin'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUQjIg1_i6t8kCHKm45_QpRyS7m.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight'), local('Montserrat-ExtraLight'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_aZA3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight'), local('Montserrat-ExtraLight'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_aZA3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight'), local('Montserrat-ExtraLight'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_aZA3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight'), local('Montserrat-ExtraLight'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_aZA3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:200;
        font-display:swap;
        src:local('Montserrat ExtraLight'), local('Montserrat-ExtraLight'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_aZA3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light'), local('Montserrat-Light'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_cJD3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light'), local('Montserrat-Light'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_cJD3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light'), local('Montserrat-Light'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_cJD3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light'), local('Montserrat-Light'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_cJD3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:300;
        font-display:swap;
        src:local('Montserrat Light'), local('Montserrat-Light'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_cJD3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Regular'), local('Montserrat-Regular'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459WRhyzbi.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Regular'), local('Montserrat-Regular'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459W1hyzbi.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Regular'), local('Montserrat-Regular'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459WZhyzbi.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Regular'), local('Montserrat-Regular'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459Wdhyzbi.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:400;
        font-display:swap;
        src:local('Montserrat Regular'), local('Montserrat-Regular'), url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium'), local('Montserrat-Medium'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_ZpC3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium'), local('Montserrat-Medium'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_ZpC3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium'), local('Montserrat-Medium'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_ZpC3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium'), local('Montserrat-Medium'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_ZpC3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:500;
        font-display:swap;
        src:local('Montserrat Medium'), local('Montserrat-Medium'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_ZpC3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_bZF3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_bZF3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_bZF3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_bZF3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:600;
        font-display:swap;
        src:local('Montserrat SemiBold'), local('Montserrat-SemiBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_bZF3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold'), local('Montserrat-Bold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold'), local('Montserrat-Bold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold'), local('Montserrat-Bold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold'), local('Montserrat-Bold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:700;
        font-display:swap;
        src:local('Montserrat Bold'), local('Montserrat-Bold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold'), local('Montserrat-ExtraBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_c5H3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold'), local('Montserrat-ExtraBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_c5H3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold'), local('Montserrat-ExtraBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_c5H3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold'), local('Montserrat-ExtraBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_c5H3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:800;
        font-display:swap;
        src:local('Montserrat ExtraBold'), local('Montserrat-ExtraBold'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_c5H3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:900;
        font-display:swap;
        src:local('Montserrat Black'), local('Montserrat-Black'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_epG3gTD_u50.woff2) format('woff2');
        unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:900;
        font-display:swap;
        src:local('Montserrat Black'), local('Montserrat-Black'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_epG3g3D_u50.woff2) format('woff2');
        unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:900;
        font-display:swap;
        src:local('Montserrat Black'), local('Montserrat-Black'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_epG3gbD_u50.woff2) format('woff2');
        unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:900;
        font-display:swap;
        src:local('Montserrat Black'), local('Montserrat-Black'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_epG3gfD_u50.woff2) format('woff2');
        unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    @font-face {
        font-family:'Montserrat';
        font-style:normal;
        font-weight:900;
        font-display:swap;
        src:local('Montserrat Black'), local('Montserrat-Black'), url(https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_epG3gnD_g.woff2) format('woff2');
        unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    *, :after, :before {
        box-sizing: border-box;
    }

    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -14px;
        margin-left: -14px;
    }

    .text-left {
        text-align: left!important;
    }

    .email-content-detail {
        margin: 50px 0;
        margin-top: 50px;
        margin-right: 0px;
        margin-bottom: 50px;
        margin-left: 0px;
    }

    .email-content {
        max-width: 450px;
        width: 90%;
    }

    .email-body {
        color: black;
    }

    .block-content {
        transition: opacity .25s ease-out;
        width: 100%;
        margin: 0 auto;
        padding: 1.25rem 1.25rem 1px;
        overflow-x: visible;
        transition-duration: 0.25s;
        transition-timing-function: ease-out;
        transition-delay: 0s;
        transition-property: opacity;
        margin-top: 0px;
        margin-right: auto;
        margin-bottom: 0px;
        margin-left: auto;
        padding-top: 1.25rem;
        padding-right: 1.25rem;
        padding-bottom: 1px;
        padding-left: 1.25rem;
    }

    .block {
        margin-bottom: 1.875rem;
        background-color: #fff;
        box-shadow: 0 .125rem rgba(0,0,0,.01);
    }

    .block , div , .content .push, .content p {
        margin-bottom: 1rem;
    }

    @media (min-width: 768px){
        .block , div , .content .push, .content p {
            margin-bottom: 1.875rem;
        }
    }

    .content {
        width: 100%;
        margin: 0 auto;
        padding: 1rem 1rem 1px;
        overflow-x: visible;
        margin-top: 0px;
        margin-right: auto;
        margin-bottom: 0px;
        margin-left: auto;
        padding-top: 1rem;
        padding-right: 1rem;
        padding-bottom: 1px;
        padding-left: 1rem;
    }

    @media (min-width: 768px){
        .content {
            width: 100%;
            margin: 0 auto;
            padding: 1.875rem 1.875rem 1px;
            overflow-x: visible;
            margin-top: 0px;
            margin-right: auto;
            margin-bottom: 0px;
            margin-left: auto;
            padding-top: 1.875rem;
            padding-right: 1.875rem;
            padding-bottom: 1px;
            padding-left: 1.875rem;
        }
    }

    #page-container > #main-container .content, #page-container > #page-footer .content, #page-container > #page-header .content, #page-container > #page-header .content-header {
        max-width: 1920px;
    }

    article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
        display: block;
    }

    #main-container {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex: 1 0 auto;
        flex: 1 0 auto;
        max-width: 100%;
        flex-grow: 1;
        flex-shrink: 0;
        flex-basis: auto;
    }

    #page-container.page-header-fixed #main-container {
        padding-top: 3.75rem;
    }

    #page-container {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        margin: 0 auto;
        width: 100%;
        height: 100%;
        min-width: 320px;
        margin-top: 0px;
        margin-right: auto;
        margin-bottom: 0px;
        margin-left: auto;
    }

    @media (min-width: 992px){
        #page-container.sidebar-o {
            padding-left: 230px;
        }
    }

    body {
        margin: 0;
        font-family: "Open Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #575757;
        text-align: left;
        background-color: #f5f5f5;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
    }

    body {
        height: 100%;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    body {
        font-family: 'Sofia_Pro_Light', 'sans-serif';
        color: #575757;
    }

    html {
        font-family: sans-serif;
        line-height: 1.15;
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: transparent;
        text-size-adjust: 100%;
    }

    html {
        height: 100%;
        font-size: 16px;
    }

    .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
        position: relative;
        width: 100%;
        padding-right: 14px;
        padding-left: 14px;
    }

    @media (min-width: 768px){
        .col-md-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
            flex-grow: 0;
            flex-shrink: 0;
            flex-basis: 100%;
        }
    }

    .block , .block  {
        box-shadow: none;
    }

    .block , div , .block-content .push, .block-content p {
        margin-bottom: 1.25rem;
    }

    .block-header {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -ms-flex-align: center;
        align-items: center;
        padding: .625rem 1.25rem;
        transition: opacity .25s ease-out;
        padding-top: 0.625rem;
        padding-right: 1.25rem;
        padding-bottom: 0.625rem;
        padding-left: 1.25rem;
        transition-duration: 0.25s;
        transition-timing-function: ease-out;
        transition-delay: 0s;
        transition-property: opacity;
    }

    .block-header-default {
        background-color: #f9f9f9;
    }

    h1, h2, h3, h4, h5, h6 {
        margin-top: 0;
        margin-bottom: 1.375rem;
    }

    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
        margin-bottom: 1.375rem;
        font-family: "Source Sans Pro","Open Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        font-weight: 600;
        line-height: 1.25;
        color: #373737;
    }

    .h3, h3 {
        font-size: 1.5rem;
    }

    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family: 'Montserrat', sans-serif;
        font-family: 'Sofia_Pro_Light', 'sans-serif';
        color: #575757;
        font-weight: 400;
    }

    .block-title {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1.75rem;
        margin: 0;
        font-size: .9375rem;
        font-weight: 600;
        line-height: 1.75;
        text-transform: uppercase;
        letter-spacing: .0625rem;
        flex-grow: 1;
        flex-shrink: 1;
        flex-basis: auto;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
    }

    .badge {
        display: inline-block;
        padding: .25em .4em;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        padding-top: 0.25em;
        padding-right: 0.4em;
        padding-bottom: 0.25em;
        padding-left: 0.4em;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
        transition-duration: 0.15s, 0.15s, 0.15s, 0.15s;
        transition-timing-function: ease-in-out, ease-in-out, ease-in-out, ease-in-out;
        transition-delay: 0s, 0s, 0s, 0s;
        transition-property: color, background-color, border-color, box-shadow;
    }

    .badge-warning {
        color: #fff;
        background-color: #f3b760;
    }

    .badge-success {
        color: #fff;
        background-color: #46c37b;
    }

    table {
        border-collapse: collapse;
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #575757;
        background-color: transparent;
    }

    .table-striped tbody tr:nth-of-type(2n + 1)  {
        background-color: #f9f9f9;
    }

    th {
        text-align: inherit;
    }

    .table td , .table th  {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #ebebeb;
        padding-top: 0.75rem;
        padding-right: 0.75rem;
        padding-bottom: 0.75rem;
        padding-left: 0.75rem;
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: rgb(235, 235, 235);
    }

    .table-vcenter td , .table-vcenter th  {
        vertical-align: middle;
    }

    .table thead th  {
        vertical-align: bottom;
        border-bottom: 2px solid #ebebeb;
        border-bottom-width: 2px;
        border-bottom-style: solid;
        border-bottom-color: rgb(235, 235, 235);
    }

    tbody , .table-borderless td , .table-borderless th , .table-borderless thead th  {
        border: 0;
        border-top-width: 0px;
        border-right-width: 0px;
        border-bottom-width: 0px;
        border-left-width: 0px;
        border-top-style: initial;
        border-right-style: initial;
        border-bottom-style: initial;
        border-left-style: initial;
        border-top-color: initial;
        border-right-color: initial;
        border-bottom-color: initial;
        border-left-color: initial;
        border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;
    }

    .table thead th  {
        font-weight: 600;
        font-size: .875rem;
        text-transform: uppercase;
        letter-spacing: .0625rem;
    }

    .table thead th , .table td  {
        font-size: 15px;
        text-transform: capitalize;
    }

    img {
        vertical-align: middle;
        border-style: none;
        border-top-style: none;
        border-right-style: none;
        border-bottom-style: none;
        border-left-style: none;
    }

    .img-avatar {
        display: inline-block!important;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }

    .img-avatar {
        display: inline-block !important;
        width: 50px !important;
        height: 50px !important;
        border-radius: 0;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
    }


</style>

<div class="bg-body-light">
    <div class="content content-full pt-2 pb-2">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h4 my-2">
                View Email Templates
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">View Email Templates</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="block">
        <div class="block-content">
            <div class="email-body" style="padding: 20px;max-width: 80%;margin: auto; font-family: DIN Next,sans-serif;">
                <div class="email-contaner" style="border: 2px solid #7daa40;padding: 25px;">
                    <div class="email-content" style="margin: auto;  text-align: center; ">
                        <div class="email-logo">
                            <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 35%">
                        </div>
                        @isset($edit)
                            <form action="{{ route('admin.emails.update', $template->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="email-content-detail" style="margin: 50px 0;">
                                    <input class="email-title " type="text" name="subject" style="margin: 0;margin-bottom: 30px;font-size: 34px; width: 100%" placeholder="subject" value="{{ $template->subject }}">
                                    <br>
                                    <textarea type="text" class="email-message-1" rows="5" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53; width: 100%" name="body" placeholder="body" >{{ $template->body }}</textarea>
                                    <br>
                                    @if($template->id == '4' || $template->id == '3')
                                        <hr>
                                        <div class="order-details text-left">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="block">
                                                        <div class="block-header block-header-default">
                                                            <h3 class="block-title">
                                                                Line Items
                                                            </h3>
                                                            @if($order->paid == '0')
                                                                <span class="badge badge-warning" style="font-size: small"> Unpaid </span>
                                                            @elseif($order->paid == '1')
                                                                <span class="badge badge-success" style="font-size: small"> Paid </span>
                                                            @elseif($order->paid == '2')
                                                                <span class="badge badge-danger" style="font-size: small;"> Refunded</span>
                                                            @endif

                                                            @if($order->status == 'Paid')
                                                                <span class="badge badge-warning" style="font-size: small"> Unfulfilled</span>
                                                            @elseif($order->status == 'unfulfilled')
                                                                <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}}</span>
                                                            @elseif($order->status == 'partially-shipped')
                                                                <span class="badge " style="font-size: small;background: darkolivegreen;color: white;"> {{ucfirst($order->status)}}</span>
                                                            @elseif($order->status == 'shipped')
                                                                <span class="badge " style="font-size: small;background: orange;color: white;"> {{ucfirst($order->status)}}</span>
                                                            @elseif($order->status == 'delivered')
                                                                <span class="badge " style="font-size: small;background: deeppink;color: white;"> {{ucfirst($order->status)}}</span>
                                                            @elseif($order->status == 'completed')
                                                                <span class="badge " style="font-size: small;background: darkslategray;color: white;"> {{ucfirst($order->status)}}</span>
                                                            @elseif($order->status == 'new')
                                                                <span class="badge badge-warning" style="font-size: small"> Draft </span>
                                                            @elseif($order->status == 'cancelled')
                                                                <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}} </span>
                                                            @else
                                                                <span class="badge badge-success" style="font-size: small">  {{ucfirst($order->status)}} </span>
                                                            @endif
                                                        </div>
                                                        <div class="block-content">

                                                            <table class="table table-borderless table-striped table-vcenter">
                                                                <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th style="width: 10%">Name</th>
                                                                    <th>Fulfilled By</th>
                                                                    <th>Cost</th>
                                                                    <th>Price X Quantity</th>
                                                                    <th>Status</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($order->line_items as $item)
                                                                    @if($item->fulfilled_by != 'store')
                                                                        <tr>
                                                                            <td>
                                                                                @if($order->custom == 0)
                                                                                    @if($item->linked_variant != null)
                                                                                        <img class="img-avatar"
                                                                                             @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                                             @else @if($item->linked_variant->has_image->isV == 1)
                                                                                             src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}"
                                                                                             @else
                                                                                             src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}"
                                                                                             @endif
                                                                                             @endif alt="">
                                                                                    @else
                                                                                        @if($item->linked_product != null)
                                                                                            @if(count($item->linked_product->has_images)>0)
                                                                                                @if($item->linked_product->has_images[0]->isV == 1)
                                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                                         src="{{asset('images/variants')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                                                @else
                                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                                         src="{{asset('images')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                                                @endif
                                                                                            @else
                                                                                                <img class="img-avatar img-avatar-variant"
                                                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                            @endif
                                                                                        @else
                                                                                            <img class="img-avatar img-avatar-variant"
                                                                                                 src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                        @endif
                                                                                    @endif

                                                                                @else
                                                                                    @if($item->linked_real_variant != null)
                                                                                        <img class="img-avatar"
                                                                                             @if($item->linked_real_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                                             @else @if($item->linked_real_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_real_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_real_variant->has_image->image}}" @endif @endif alt="">
                                                                                    @else
                                                                                        @if($item->linked_real_product != null)
                                                                                            @if(count($item->linked_real_product->has_images)>0)
                                                                                                @if($item->linked_real_product->has_images[0]->isV == 1)
                                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                                         src="{{asset('images/variants')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                                                @else
                                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                                         src="{{asset('images')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                                                @endif
                                                                                            @else
                                                                                                <img class="img-avatar img-avatar-variant"
                                                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                            @endif
                                                                                        @else
                                                                                            <img class="img-avatar img-avatar-variant"
                                                                                                 src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </td>


                                                                            <td style="width: 30%">
                                                                                {{$item->name}}

                                                                            </td>
                                                                            <td>
                                                                                @if($item->fulfilled_by == 'store')
                                                                                    <span class="badge badge-danger"> Store</span>
                                                                                @elseif ($item->fulfilled_by == 'Fantasy')
                                                                                    <span class="badge badge-success"> WeFullFill </span>
                                                                                @else
                                                                                    <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                                                                @endif
                                                                            </td>

                                                                            <td>{{number_format($item->cost,2)}}  X {{$item->quantity}}  USD</td>
                                                                            <td>{{$item->price}} X {{$item->quantity}}  USD </td>
                                                                            <td>
                                                                                @if($item->fulfillment_status == null)
                                                                                    <span class="badge badge-warning"> Unfulfilled</span>
                                                                                @elseif($item->fulfillment_status == 'partially-fulfilled')
                                                                                    <span class="badge badge-danger"> Partially Fulfilled</span>
                                                                                @else
                                                                                    <span class="badge badge-success"> Fulfilled</span>
                                                                                @endif
                                                                            </td>

                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                                </tbody>

                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="block">
                                                        <div class="block-header block-header-default">
                                                            <h3 class="block-title">
                                                                Summary
                                                            </h3>
                                                        </div>
                                                        <div class="block-content">
                                                            <table class="table table-borderless table-vcenter">
                                                                <thead>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>
                                                                        Subtotal ({{count($order->line_items)}} items)
                                                                    </td>
                                                                    <td align="right">
                                                                        {{number_format($order->cost_to_pay - $order->shipping_price,2)}} USD
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        Shipping Price
                                                                    </td>
                                                                    <td align="right">
                                                                        {{number_format($order->shipping_price,2)}} USD
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        Total Cost
                                                                    </td>
                                                                    <td align="right">
                                                                        {{number_format($order->cost_to_pay,2)}} USD
                                                                    </td>
                                                                </tr>
                                                                </tbody>


                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($template->id == '13')
                                        <div class="text-left">
                                            <label for="" style="color: #7daa40 !important;">Select Products</label>
                                        </div>
                                        <select class="@error('type') is-invalid @enderror js-select2 form-control" name="products[]" style="width: 100%; border-radius: 0 !important;" data-placeholder="Select Products.." multiple>
                                           @foreach($products as $product)
                                                @php
                                                    $prods = json_decode($template->products);
                                                @endphp
                                                <option value="{{ $product->id }}"
                                                    @if(in_array($product->id, $prods))
                                                        selected
                                                    @endif
                                                >{{ $product->title }}</option>
                                           @endforeach

                                        </select>
                                        <br><br><br>
                                    @endif

                                    @if($template->id == '1' || $template->id == '2')
                                        <a class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                                    @else
                                        <a class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Details</a>
                                    @endif
                                </div>
                                <button type="submit" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none; background-color: #7daa40; color: #ffffff; margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Update</button>
                            </form>
                        @else
                            <div class="email-content-detail" style="margin: 50px 0;">
                                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">{{ $template->subject }}</h1>
                                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >{{ $template->body }} </p>
                                @if($template->id == '4' || $template->id == '3')
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 XXsnipcss_extracted_selector_selectionXX">
                                        <div class="block">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">
                                                    Line Items
                                                </h3>
                                                @if($order->paid == '0')
                                                    <span class="badge badge-warning" style="font-size: small"> Unpaid </span>
                                                @elseif($order->paid == '1')
                                                    <span class="badge badge-success" style="font-size: small"> Paid </span>
                                                @elseif($order->paid == '2')
                                                    <span class="badge badge-danger" style="font-size: small;"> Refunded</span>
                                                @endif

                                                @if($order->status == 'Paid')
                                                    <span class="badge badge-warning" style="font-size: small"> Unfulfilled</span>
                                                @elseif($order->status == 'unfulfilled')
                                                    <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}}</span>
                                                @elseif($order->status == 'partially-shipped')
                                                    <span class="badge " style="font-size: small;background: darkolivegreen;color: white;"> {{ucfirst($order->status)}}</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge " style="font-size: small;background: orange;color: white;"> {{ucfirst($order->status)}}</span>
                                                @elseif($order->status == 'delivered')
                                                    <span class="badge " style="font-size: small;background: deeppink;color: white;"> {{ucfirst($order->status)}}</span>
                                                @elseif($order->status == 'completed')
                                                    <span class="badge " style="font-size: small;background: darkslategray;color: white;"> {{ucfirst($order->status)}}</span>
                                                @elseif($order->status == 'new')
                                                    <span class="badge badge-warning" style="font-size: small"> Draft </span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}} </span>
                                                @else
                                                    <span class="badge badge-success" style="font-size: small">  {{ucfirst($order->status)}} </span>
                                                @endif
                                            </div>
                                            <div class="block-content">
                                                <table class="table table-borderless table-striped table-vcenter">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                        </th>
                                                        <th style="width: 10%">Name</th>
                                                        <th>Fulfilled By</th>
                                                        <th>Cost</th>
                                                        <th>Price X Quantity</th>
                                                        <th>Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($order->line_items as $item)
                                                        @if($item->fulfilled_by != 'store')
                                                            <tr>
                                                                <td>
                                                                    @if($order->custom == 0)
                                                                        @if($item->linked_variant != null)
                                                                            <img class="img-avatar"
                                                                                 @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                                 @else @if($item->linked_variant->has_image->isV == 1)
                                                                                 src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}"
                                                                                 @else
                                                                                 src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}"
                                                                                 @endif
                                                                                 @endif alt="">
                                                                        @else
                                                                            @if($item->linked_product != null)
                                                                                @if(count($item->linked_product->has_images)>0)
                                                                                    @if($item->linked_product->has_images[0]->isV == 1)
                                                                                        <img class="img-avatar img-avatar-variant"
                                                                                             src="{{asset('images/variants')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                                    @else
                                                                                        <img class="img-avatar img-avatar-variant"
                                                                                             src="{{asset('images')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                                    @endif
                                                                                @else
                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                         src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                @endif
                                                                            @else
                                                                                <img class="img-avatar img-avatar-variant"
                                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                            @endif
                                                                        @endif

                                                                    @else
                                                                        @if($item->linked_real_variant != null)
                                                                            <img class="img-avatar"
                                                                                 @if($item->linked_real_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                                 @else @if($item->linked_real_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_real_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_real_variant->has_image->image}}" @endif @endif alt="">
                                                                        @else
                                                                            @if($item->linked_real_product != null)
                                                                                @if(count($item->linked_real_product->has_images)>0)
                                                                                    @if($item->linked_real_product->has_images[0]->isV == 1)
                                                                                        <img class="img-avatar img-avatar-variant"
                                                                                             src="{{asset('images/variants')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                                    @else
                                                                                        <img class="img-avatar img-avatar-variant"
                                                                                             src="{{asset('images')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                                    @endif
                                                                                @else
                                                                                    <img class="img-avatar img-avatar-variant"
                                                                                         src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                                @endif
                                                                            @else
                                                                                <img class="img-avatar img-avatar-variant"
                                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </td>


                                                                <td style="width: 30%">
                                                                    {{$item->name}}

                                                                </td>
                                                                <td>
                                                                    @if($item->fulfilled_by == 'store')
                                                                        <span class="badge badge-danger"> Store</span>
                                                                    @elseif ($item->fulfilled_by == 'Fantasy')
                                                                        <span class="badge badge-success"> WeFullFill </span>
                                                                    @else
                                                                        <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                                                    @endif
                                                                </td>

                                                                <td>{{number_format($item->cost,2)}}  X {{$item->quantity}}  USD</td>
                                                                <td>{{$item->price}} X {{$item->quantity}}  USD </td>
                                                                <td>
                                                                    @if($item->fulfillment_status == null)
                                                                        <span class="badge badge-warning"> Unfulfilled</span>
                                                                    @elseif($item->fulfillment_status == 'partially-fulfilled')
                                                                        <span class="badge badge-danger"> Partially Fulfilled</span>
                                                                    @else
                                                                        <span class="badge badge-success"> Fulfilled</span>
                                                                    @endif
                                                                </td>

                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="block">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Summary</h3>
                                            </div>
                                            <div class="block-content">
                                                <table class="table table-borderless table-vcenter">
                                                    <thead>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            Subtotal ({{count($order->line_items)}} items)
                                                        </td>
                                                        <td align="right">
                                                            {{number_format($order->cost_to_pay - $order->shipping_price,2)}} USD
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Shipping Price
                                                        </td>
                                                        <td align="right">
                                                            {{number_format($order->shipping_price,2)}} USD
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            Total Cost
                                                        </td>
                                                        <td align="right">
                                                            {{number_format($order->cost_to_pay,2)}} USD
                                                        </td>
                                                    </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @endif

                                @if($template->id == '1' || $template->id == '2')
                                    <a class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                                @else
                                    <a class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Details</a>
                                @endif
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
