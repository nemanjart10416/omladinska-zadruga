<?php

class Templates {
    /**
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $message
     * @return string
     */
    public static function contactTemplate(string $name, string $email, string $phone, string $message): string {
        return '
    <!DOCTYPE html>
    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
        <head>
        <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <!--[if mso]>
            <xml>
                <o:OfficeDocumentSettings>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                    <o:AllowPNG/>
                </o:OfficeDocumentSettings>
            </xml><![endif]-->
            <style>
                * {
                    box-sizing: border-box
                }
        
                body {
                    margin: 0;
                    padding: 0
                }
        
                a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: inherit !important
                }
        
                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none
                }
        
                p {
                    line-height: inherit
                }
        
                .desktop_hide, .desktop_hide table {
                    mso-hide: all;
                    display: none;
                    max-height: 0;
                    overflow: hidden
                }
        
                .image_block img + div {
                    display: none
                }
        
                @media (max-width: 620px) {
                    .row-content {
                        width: 100% !important
                    }
        
                    .mobile_hide {
                        display: none
                    }
        
                    .stack .column {
                        width: 100%;
                        display: block
                    }
        
                    .mobile_hide {
                        min-height: 0;
                        max-height: 0;
                        max-width: 0;
                        overflow: hidden;
                        font-size: 0
                    }
        
                    .desktop_hide, .desktop_hide table {
                        display: table !important;
                        max-height: none !important
                    }
                }
            </style>
        </head>
        <body style="background-color:#fff;margin:0;padding:0;-webkit-text-size-adjust:none;text-size-adjust:none">
            <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;background-color:#fff">
                <tbody>
                <tr>
                    <td>
                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                            <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:600px" width="600">
                                        <tbody>
                                        <tr>
                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:5px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                    <tr>
                                                        <td class="pad" style="padding-bottom:10px;padding-top:10px;width:100%;padding-right:0;padding-left:0">
                                                            <div class="alignment" align="center" style="line-height:10px"><img
                                                                    src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/0db9f180-d222-4b2b-9371-cf9393bf4764/0bd8b69e-4024-4f26-9010-6e2a146401fb/Email%20Templates%20Assets%20Folder/Logos/yourlogohere_icon_black.png_thumb.png" style="display:block;height:auto;border:0;width:48px;max-width:100%" width="48" alt="Alternate text" title="Alternate text"></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="divider_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                                                    <tr>
                                                        <td class="pad">
                                                            <div class="alignment" align="center">
                                                                <table border="0" cellpadding="0" cellspacing="0"
                                                                       role="presentation" width="100%"
                                                                       style="mso-table-lspace:0;mso-table-rspace:0">
                                                                    <tr>
                                                                        <td class="divider_inner" style="font-size:1px;line-height:1px;border-top:1px solid #dedede">
                                                                            <span>&#8202;</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0">
                            <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;color:#000;width:600px" width="600">
                                        <tbody>
                                        <tr>
                                            <td class="column column-1" width="100%" style="mso-table-lspace:0;mso-table-rspace:0;font-weight:400;text-align:left;padding-bottom:30px;padding-left:20px;padding-right:20px;padding-top:5px;vertical-align:top;border-top:0;border-right:0;border-bottom:0;border-left:0">
                                                <table class="text_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                    <tr>
                                                        <td class="pad">
                                                            <div style="font-family:sans-serif">
                                                                <div class style="font-size:12px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;mso-line-height-alt:14.399999999999999px;color:#333;line-height:1.2">
                                                                    <p style="margin:0;font-size:14px;text-align:center;mso-line-height-alt:16.8px">
                                                                        <span style="font-size:30px;">NEW MESSAGE</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="text_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace:0;mso-table-rspace:0;word-break:break-word">
                                                    <tr>
                                                        <td class="pad">
                                                            <div style="font-family:sans-serif">
                                                                <div class style="font-size:12px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;mso-line-height-alt:21.6px;color:#333;line-height:1.8">
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:30.6px">
                                                                        <span style="font-size:17px;">You have new contact message from contact form on website airport taxi wien</span>
                                                                    </p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:21.6px">&nbsp;</p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:30.6px">
                                                                        <span style="font-size:17px;">name: ' . $name . '</span></p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:30.6px">
                                                                        <span style="font-size:17px;">email: ' . $email . '</span></p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:30.6px">
                                                                        <span style="font-size:17px;">phone: ' . $phone . '</span></p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:21.6px">
                                                                        &nbsp;
                                                                    </p>
                                                                    <p style="margin:0;text-align:center;mso-line-height-alt:30.6px">
                                                                        <span style="font-size:17px;">' . $message . '</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table><!-- End -->
            <div style="background-color:transparent;">
                <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;"
                     class="block-grid ">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <!--[if (mso)|(IE)]>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="background-color:transparent;" align="center">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width: 500px;">
                                        <tr class="layout-full-width" style="background-color:transparent;"><![endif]-->
                        <!--[if (mso)|(IE)]>
                        <td align="center" width="500"
                            style=" width:500px; padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:15px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;"
                            valign="top"><![endif]-->
                        <div class="col num12" style="min-width: 320px;max-width: 500px;display: table-cell;vertical-align: top;">
                            <div style="background-color: transparent; width: 100% !important;">
                                <!--[if (!mso)&(!IE)]><!-->
                                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:15px; padding-bottom:15px; padding-right: 0px; padding-left: 0px;">
                                    <!--<![endif]-->
            
            
                                    <div align="center" class="img-container center  autowidth "
                                         style="padding-right: 0px;  padding-left: 0px;">
                                        <!--[if mso]>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding-right: 0px; padding-left: 0px;" align="center"><![endif]-->
            
                                        <a href="https://goo.gl/sDhD5J" target="_blank"
                                           title="https://www.enginemailer.com/?utm_source=newsletter&utm_medium=email&utm_campaign=ff_footer">
                                            <img class="center  autowidth " align="center" border="0"
                                                 src="https://enginemailerblobv1.blob.core.windows.net/images/08b941a9-ecfa-46d2-b0d3-e95efa91fe41/enginemailer-forever-free.png"
                                                 alt="Image"
                                                 title="https://www.enginemailer.com/?utm_source=newsletter&utm_medium=email&utm_campaign=ff_footer"
                                                 style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 168px"
                                                 width="168"/>
                                        </a>
                                        <!--[if mso]></td></tr></table><![endif]-->
                                    </div>
            
            
                                    <!--[if (!mso)&(!IE)]><!-->
                                </div><!--<![endif]-->
                            </div>
                        </div>
                        <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
                    </div>
                </div>
            </div>
        </body>
    </html>
';
    }
}