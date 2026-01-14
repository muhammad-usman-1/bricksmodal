@extends('layouts.admin')
@section('content')
<link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e6e7eb;
        --shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .talent-shell { padding: 8px 0 22px; }
    .top-actions {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        margin-bottom: 12px;
        position: relative;
    }
    .top-actions-left { justify-self: start; }
    .top-actions-center { justify-self: center; }
    .top-actions-right { justify-self: end; display: flex; gap: 10px; }

    .back-link {
        color: var(--ink-700);
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
         
        font-weight: 600;

    }
    .back-link:hover {
        background: #f9fafb;
        color: var(--ink-900);
        text-decoration: none;
        border-color: #cbd5e1;
    }
    .edit-btn { background: #0f1524; color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-size: 13px; text-decoration: none; box-shadow: 0 10px 20px rgba(0,0,0,0.12); cursor: pointer; font-weight: 600; }

    .tabs { display: flex; gap: 14px; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--border); padding-bottom: 8px; }
    .tab-link { font-size: 13px; color: var(--ink-700); padding: 6px 0; text-decoration: none; position: relative; cursor: pointer; }
    .tab-link.active { color: var(--ink-900); font-weight: 700; }
    .tab-link.active::after { content: ''; position: absolute; left: 0; right: 0; bottom: -9px; height: 2px; background: #0f1524; }

    .section-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow); padding: 14px; margin-bottom: 14px; }
    .section-title { font-weight: 600; color: var(--ink-900); font-size: 14px; margin-bottom: 12px; }

    .upload-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
    .upload-tile {
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        width: 100%;
        aspect-ratio: 3 / 4;
        min-height: 280px;
        display: grid;
        place-items: center;
        color: var(--ink-500);
        text-align: center;

        position: relative;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .upload-tile.is-editable:hover { border-color: #0f172a; background: #f1f5f9; cursor: pointer; }
    .upload-tile.is-editable.drag-over { border-color: #10B981; background: #d1fae5; border-width: 2px; }
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(15, 23, 42, 0.85);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        font-size: 14px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    .remove-image-btn:hover {
        background: rgba(15, 23, 42, 1);
        transform: scale(1.1);
    }
    .is-editing .upload-tile .remove-image-btn {
        display: flex;
    }
    .upload-tile .remove-image-btn {
        display: none;
    }
    .upload-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        object-position: center;
    }

    .upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        color: #fff;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        backdrop-filter: blur(2px);
    }
    .is-editing .upload-tile.is-editable .upload-overlay { display: flex; }
    .upload-tile.is-editable input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 10; }

    .upload-placeholder { display: grid; place-items: center; gap: 8px; }
    .upload-placeholder i { font-size: 22px; color: #9ca3af; }
    .upload-support { font-size: 10px; color: #9ca3af; }

    .info-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .info-table { width: 100%; font-size: 12px; color: var(--ink-700); }
    .info-table td { padding: 6px 0; }
    .info-table td:first-child { color: var(--ink-500); width: 46%; }
    .info-table td:last-child { color: var(--ink-700); }
    .info-table .not-set { color: #3b82f6; }

    .action-bar { margin-top: 12px; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-reject { background: #f6f7fb; color: #b91c1c; border: 1px solid #f4c7c7; border-radius: 6px; padding: 10px 38px; font-size: 18px; margin-bottom:10px; display: inline-flex; align-items: center; gap: 8px; }
    .btn-approve { background: #10B981; color: #fff; border: none; border-radius: 6px; padding: 10px 38px; font-size: 18px;  margin-bottom:10px; display: inline-flex; align-items: center; gap: 8px; }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
    .reviews-wrap { margin: 12px 0 18px; }
    .reviews-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 14px 16px; }
    .overview-card { display: flex; align-items: center; gap: 10px; background: #f9fafb; border: 1px solid #edf0f3; border-radius: 12px; padding: 12px 14px; margin-bottom: 16px; }
    .overview-icon { width: 36px; height: 36px; border-radius: 8px; background: #2C2C2E; display: grid; place-items: center; color: #fff; font-size: 14px; }
    .overview-title { margin: 0; font-weight: 600; color: #0f172a; font-size: 14px; }
    .overview-sub { margin: 0; color: #6b7280; font-size: 12px; }
    .review-list { display: flex; flex-direction: column; gap: 12px; }
    .review-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; background: #fff; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .review-text { display: flex; flex-direction: column; gap: 4px; flex: 1; }
    .review-title { margin: 0; color: #0f172a; font-weight: 600; font-size: 14px; }
    .star-row { display: flex; gap: 2px; margin: 2px 0; }
    .star { color: #d1d5db; font-size: 13px; }
    .star.filled { color: #000; }
    .review-meta { display: flex; align-items: center; gap: 8px; color: #6b7280; font-size: 12px; flex-wrap: wrap; }
    .review-meta span { display: inline-flex; align-items: center; }
    .meta-separator { color: #d1d5db; margin: 0 2px; }
    .status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .status-reviewed { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fed7aa; color: #c2410c; }
    .review-action { display: inline-flex; align-items: center; justify-content: center; background: #2C2C2E; color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-size: 12px; font-weight: 600; text-decoration: none; min-width: 110px; white-space: nowrap; }
    .review-action:hover { text-decoration: none; background: #1a1a1c; color: #fff; }

    /* Shoot & billing */
    .shoots-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 14px 16px; margin-bottom: 16px; }
    .shoot-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 16px; }
    .shoot-title { display: flex; align-items: center; gap: 10px; color: #0f172a; font-weight: 600; font-size: 14px; margin: 0; }
    .shoot-title i { width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; background: #2C2C2E; color: #fff; font-size: 14px; }
    .shoot-sub { color: #6b7280; font-size: 12px; margin: 0; }
    .count-box { text-align: right; }
    .count-label { color: #9ca3af; font-size: 11px; margin: 0; }
    .count-value { color: #0f172a; font-weight: 600; font-size: 16px; margin: 0; }
    .shoot-list { display: flex; flex-direction: column; gap: 12px; }
    .shoot-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; display: grid; grid-template-columns: 1fr auto; gap: 16px; background: #fff; }
    .shoot-main { display: flex; flex-direction: column; gap: 4px; }
    .shoot-top { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .shoot-name { margin: 0; color: #0f172a; font-weight: 600; font-size: 14px; }
    .pill { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .pill-success { background: #d1fae5; color: #065f46; }
    .pill-warning { background: #fed7aa; color: #c2410c; }
    .pill-muted { background: #f3f4f6; color: #6b7280; }
    .shoot-meta { display: flex; flex-wrap: wrap; gap: 6px; color: #6b7280; font-size: 12px; align-items: center; }
    .shoot-meta-item { display: inline-flex; align-items: center; gap: 4px; }
    .shoot-meta-item i { font-size: 11px; }
    .meta-separator { color: #d1d5db; margin: 0 2px; }
    .shoot-role { color: #6b7280; font-size: 12px; margin: 4px 0 0 0; }
    .shoot-rating { display: inline-flex; align-items: center; gap: 4px; color: #000; font-size: 12px; font-weight: 600; }
    .shoot-rating i { color: #f59e0b; font-size: 12px; }
    .shoot-amount { text-align: right; display: flex; flex-direction: column; gap: 2px; justify-content: center; }
    .amount-value { margin: 0; color: #0f172a; font-weight: 600; font-size: 16px; }
    .amount-role { margin: 0; color: #6b7280; font-size: 11px; text-align: right; }

    .billing-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 24px rgba(15,23,42,0.06); padding: 14px 16px; }
    .billing-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
    .billing-title { display: flex; align-items: center; gap: 8px; margin: 0; color: #0f172a; font-weight: 700; font-size: 14px; }
    .billing-title i { width: 28px; height: 28px; border-radius: 8px; display: grid; place-items: center; background: #3b82f6; color: #fff; font-size: 12px; }
    .badge-blue { background: #3b82f6; color: #fff; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 600; }
    .billing-total { text-align: right; }
    .billing-label { color: #9ca3af; font-size: 11px; margin: 0; }
    .billing-value { color: #0f172a; font-weight: 700; font-size: 16px; margin: 0; }
    .billing-list { display: flex; flex-direction: column; gap: 8px; }
    .billing-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; display: grid; grid-template-columns: 1fr auto; gap: 8px; background: #fff; }
    .billing-main { display: flex; flex-direction: column; gap: 4px; }
    .billing-name { margin: 0; color: #0f172a; font-weight: 700; font-size: 13px; }
    .billing-meta { color: #6b7280; font-size: 12px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .badge-paid { background: #ecfdf3; color: #15803d; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 600; }
    .billing-amount { text-align: right; color: #0f172a; font-weight: 700; font-size: 14px; align-self: center; }

    .edit-mode-only { display: none !important; }
    .is-editing .edit-mode-only { display: block !important; }
    .is-editing .display-mode-only { display: none !important; }
    .top-actions-right-display { display: flex; gap: 10px; }

    /* Actions (3-dot) menu */
    .actions-menu { position: relative; }
    .actions-trigger {
        width: 40px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: var(--ink-700);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    .actions-trigger:hover { background: #f9fafb; border-color: #cbd5e1; color: var(--ink-900); }
    .actions-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 180px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 18px 32px rgba(15,23,42,0.12);
        padding: 6px 0;
        display: none;
        z-index: 50;
        text-align: left;
    }
    .actions-dropdown.show { display: block; }
    .actions-item {
        width: 100%;
        border: none;
        background: transparent;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #111827;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }
    .actions-item:hover { background: #f3f4f6; text-decoration: none; color: #111827; }
    .actions-item i { width: 16px; text-align: center; color: #6b7280; }
    .actions-item.danger { color: #b91c1c; }
    .actions-item.danger i { color: #b91c1c; }

    .inline-edit-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 12px;
        color: var(--ink-700);
        background: #fff;
    }
    .inline-edit-input:focus { border-color: #0f172a; outline: none; box-shadow: 0 0 0 2px rgba(15,23,42,0.1); }

    .save-btn { background: #10B981; color: #fff; border: none !important; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: none; }
    .save-btn:hover { background: #059669; }
    .save-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .cancel-btn { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer; }

    @media (max-width: 640px) {
        .top-actions { display: flex; flex-direction: column; gap: 12px; }
        .top-actions-left, .top-actions-center, .top-actions-right { justify-self: stretch; width: 100%; display: flex; justify-content: center; }
        .tabs { flex-wrap: wrap; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $notSet = trans('global.not_set');

    // Country code to name mapping
    $countries = [
        'af' => 'Afghanistan', 'al' => 'Albania', 'dz' => 'Algeria', 'as' => 'American Samoa', 'ad' => 'Andorra', 'ao' => 'Angola', 'ai' => 'Anguilla', 'aq' => 'Antarctica', 'ag' => 'Antigua and Barbuda', 'ar' => 'Argentina', 'am' => 'Armenia', 'aw' => 'Aruba', 'au' => 'Australia', 'at' => 'Austria', 'az' => 'Azerbaijan',
        'bs' => 'Bahamas', 'bh' => 'Bahrain', 'bd' => 'Bangladesh', 'bb' => 'Barbados', 'by' => 'Belarus', 'be' => 'Belgium', 'bz' => 'Belize', 'bj' => 'Benin', 'bm' => 'Bermuda', 'bt' => 'Bhutan', 'bo' => 'Bolivia', 'ba' => 'Bosnia and Herzegovina', 'bw' => 'Botswana', 'br' => 'Brazil', 'io' => 'British Indian Ocean Territory', 'bn' => 'Brunei', 'bg' => 'Bulgaria', 'bf' => 'Burkina Faso', 'bi' => 'Burundi',
        'cv' => 'Cabo Verde', 'kh' => 'Cambodia', 'cm' => 'Cameroon', 'ca' => 'Canada', 'ky' => 'Cayman Islands', 'cf' => 'Central African Republic', 'td' => 'Chad', 'cl' => 'Chile', 'cn' => 'China', 'cx' => 'Christmas Island', 'cc' => 'Cocos Islands', 'co' => 'Colombia', 'km' => 'Comoros', 'cg' => 'Congo', 'cd' => 'Congo (DRC)', 'ck' => 'Cook Islands', 'cr' => 'Costa Rica', 'ci' => 'Côte d\'Ivoire', 'hr' => 'Croatia', 'cu' => 'Cuba', 'cw' => 'Curaçao', 'cy' => 'Cyprus', 'cz' => 'Czech Republic',
        'dk' => 'Denmark', 'dj' => 'Djibouti', 'dm' => 'Dominica', 'do' => 'Dominican Republic',
        'ec' => 'Ecuador', 'eg' => 'Egypt', 'sv' => 'El Salvador', 'gq' => 'Equatorial Guinea', 'er' => 'Eritrea', 'ee' => 'Estonia', 'sz' => 'Eswatini', 'et' => 'Ethiopia',
        'fk' => 'Falkland Islands', 'fo' => 'Faroe Islands', 'fj' => 'Fiji', 'fi' => 'Finland', 'fr' => 'France', 'gf' => 'French Guiana', 'pf' => 'French Polynesia', 'tf' => 'French Southern Territories',
        'ga' => 'Gabon', 'gm' => 'Gambia', 'ge' => 'Georgia', 'de' => 'Germany', 'gh' => 'Ghana', 'gi' => 'Gibraltar', 'gr' => 'Greece', 'gl' => 'Greenland', 'gd' => 'Grenada', 'gp' => 'Guadeloupe', 'gu' => 'Guam', 'gt' => 'Guatemala', 'gg' => 'Guernsey', 'gn' => 'Guinea', 'gw' => 'Guinea-Bissau', 'gy' => 'Guyana',
        'ht' => 'Haiti', 'hm' => 'Heard Island', 'hn' => 'Honduras', 'hk' => 'Hong Kong', 'hu' => 'Hungary',
        'is' => 'Iceland', 'in' => 'India', 'id' => 'Indonesia', 'ir' => 'Iran', 'iq' => 'Iraq', 'ie' => 'Ireland', 'im' => 'Isle of Man', 'il' => 'Israel', 'it' => 'Italy',
        'jm' => 'Jamaica', 'jp' => 'Japan', 'je' => 'Jersey', 'jo' => 'Jordan',
        'kz' => 'Kazakhstan', 'ke' => 'Kenya', 'ki' => 'Kiribati', 'kp' => 'Korea (North)', 'kr' => 'Korea (South)', 'kw' => 'Kuwait', 'kg' => 'Kyrgyzstan',
        'la' => 'Laos', 'lv' => 'Latvia', 'lb' => 'Lebanon', 'ls' => 'Lesotho', 'lr' => 'Liberia', 'ly' => 'Libya', 'li' => 'Liechtenstein', 'lt' => 'Lithuania', 'lu' => 'Luxembourg',
        'mo' => 'Macao', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mv' => 'Maldives', 'ml' => 'Mali', 'mt' => 'Malta', 'mh' => 'Marshall Islands', 'mq' => 'Martinique', 'mr' => 'Mauritania', 'mu' => 'Mauritius', 'yt' => 'Mayotte', 'mx' => 'Mexico', 'fm' => 'Micronesia', 'md' => 'Moldova', 'mc' => 'Monaco', 'mn' => 'Mongolia', 'me' => 'Montenegro', 'ms' => 'Montserrat', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'mm' => 'Myanmar',
        'na' => 'Namibia', 'nr' => 'Nauru', 'np' => 'Nepal', 'nl' => 'Netherlands', 'nc' => 'New Caledonia', 'nz' => 'New Zealand', 'ni' => 'Nicaragua', 'ne' => 'Niger', 'ng' => 'Nigeria', 'nu' => 'Niue', 'nf' => 'Norfolk Island', 'mk' => 'North Macedonia', 'mp' => 'Northern Mariana Islands', 'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan', 'pw' => 'Palau', 'ps' => 'Palestine', 'pa' => 'Panama', 'pg' => 'Papua New Guinea', 'py' => 'Paraguay', 'pe' => 'Peru', 'ph' => 'Philippines', 'pn' => 'Pitcairn', 'pl' => 'Poland', 'pt' => 'Portugal', 'pr' => 'Puerto Rico',
        'qa' => 'Qatar',
        're' => 'Réunion', 'ro' => 'Romania', 'ru' => 'Russia', 'rw' => 'Rwanda',
        'bl' => 'Saint Barthélemy', 'sh' => 'Saint Helena', 'kn' => 'Saint Kitts and Nevis', 'lc' => 'Saint Lucia', 'mf' => 'Saint Martin', 'pm' => 'Saint Pierre and Miquelon', 'vc' => 'Saint Vincent and the Grenadines', 'ws' => 'Samoa', 'sm' => 'San Marino', 'st' => 'São Tomé and Príncipe', 'sa' => 'Saudi Arabia', 'sn' => 'Senegal', 'rs' => 'Serbia', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sg' => 'Singapore', 'sx' => 'Sint Maarten', 'sk' => 'Slovakia', 'si' => 'Slovenia', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa', 'gs' => 'South Georgia', 'ss' => 'South Sudan', 'es' => 'Spain', 'lk' => 'Sri Lanka', 'sd' => 'Sudan', 'sr' => 'Suriname', 'sj' => 'Svalbard and Jan Mayen', 'se' => 'Sweden', 'ch' => 'Switzerland', 'sy' => 'Syria',
        'tw' => 'Taiwan', 'tj' => 'Tajikistan', 'tz' => 'Tanzania', 'th' => 'Thailand', 'tl' => 'Timor-Leste', 'tg' => 'Togo', 'tk' => 'Tokelau', 'to' => 'Tonga', 'tt' => 'Trinidad and Tobago', 'tn' => 'Tunisia', 'tr' => 'Turkey', 'tm' => 'Turkmenistan', 'tc' => 'Turks and Caicos Islands', 'tv' => 'Tuvalu',
        'ug' => 'Uganda', 'ua' => 'Ukraine', 'ae' => 'United Arab Emirates', 'gb' => 'United Kingdom', 'um' => 'United States Minor Outlying Islands', 'us' => 'United States', 'uy' => 'Uruguay', 'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu', 've' => 'Venezuela', 'vn' => 'Vietnam', 'vg' => 'Virgin Islands (British)', 'vi' => 'Virgin Islands (U.S.)',
        'wf' => 'Wallis and Futuna', 'eh' => 'Western Sahara',
        'ye' => 'Yemen',
        'zm' => 'Zambia', 'zw' => 'Zimbabwe'
    ];

    $headshots = [
        'headshot_center_path' => 'Headshot (Center)',
        'headshot_left_path'   => 'Headshot (Left)',
        'headshot_right_path'  => 'Headshot (Right)',
    ];

    $fullBody = [
        'full_body_front_path' => 'Full Body (Front)',
        'full_body_right_path' => 'Full Body (Right)',
        'full_body_back_path'  => 'Full Body (Back)',
    ];

    $idDocs = [
        'id_front_path' => 'ID Front',
        'id_back_path'  => 'ID Back',
    ];

    $resolveUrl = function ($path) {
        if (! $path) {
            return null;
        }

        $isAbsolute = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//']);
        $awsUrl = rtrim((string) env('AWS_URL'), '/');
        $disk = config('filesystems.default', 'public');
        $storage = \Illuminate\Support\Facades\Storage::disk($disk);

        // If absolute and matches AWS_URL, try to generate a signed URL for private buckets
        if ($isAbsolute && $awsUrl && \Illuminate\Support\Str::startsWith($path, $awsUrl)) {
            $relative = ltrim(\Illuminate\Support\Str::after($path, $awsUrl), '/');
            try {
                return $storage->temporaryUrl($relative, now()->addMinutes(60));
            } catch (\Exception $e) {
                try {
                    return $storage->url($relative);
                } catch (\Exception $e2) {
                    return $path; // fallback to given URL
                }
            }
        }

        if ($isAbsolute) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        // Prefer CDN/AWS_URL mapping; url() respects AWS_URL when configured.
        try {
            return $storage->url($cleanPath);
        } catch (\Exception $e) {
            // Fallback to signed URL if url() fails (e.g., private bucket without AWS_URL)
            try {
                return $storage->temporaryUrl($cleanPath, now()->addMinutes(60));
            } catch (\Exception $e2) {
                return null;
            }
        }
    };

    // Field configurations for easy rendering
    $profileFields = [
        ['label' => 'First name', 'name' => 'first_name', 'value' => $talentProfile->first_name, 'type' => 'text'],
        ['label' => 'Last name', 'name' => 'last_name', 'value' => $talentProfile->last_name, 'type' => 'text'],
        ['label' => 'Nationality', 'name' => 'nationality', 'value' => $talentProfile->nationality, 'type' => 'nationality'],
        ['label' => 'Date of birth', 'name' => 'date_of_birth', 'value' => optional($talentProfile->date_of_birth)->format('Y-m-d'), 'type' => 'date'],
        ['label' => 'Gender', 'name' => 'gender', 'value' => $talentProfile->gender, 'type' => 'select', 'options' => ['male' => 'Male', 'female' => 'Female'], 'data-field' => 'gender'],
    ];

    $accountFields = [
        ['label' => 'WhatsApp number', 'name' => 'whatsapp_number', 'value' => $talentProfile->whatsapp_number, 'type' => 'text', 'required' => true],
        ['label' => 'Mobile number', 'name' => 'mobile_number', 'value' => $talentProfile->mobile_number, 'type' => 'text'],
        ['label' => 'Daily rate', 'name' => 'daily_rate', 'value' => $talentProfile->daily_rate, 'type' => 'number', 'required' => true],
        ['label' => 'Hourly rate', 'name' => 'hourly_rate', 'value' => $talentProfile->hourly_rate, 'type' => 'number'],
        ['label' => 'Verification status', 'name' => 'verification_status', 'value' => $talentProfile->verification_status, 'type' => 'select', 'options' => \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT],
        ['label' => 'Verification notes', 'name' => 'verification_notes', 'value' => $talentProfile->verification_notes, 'type' => 'textarea'],
        ['label' => 'Card holder name', 'name' => 'card_holder_name', 'value' => $talentProfile->card_holder_name, 'type' => 'text'],
    ];

    $measurementFields = [
        ['label' => 'Height', 'name' => 'height', 'value' => $talentProfile->height, 'type' => 'number'],
        ['label' => 'Weight', 'name' => 'weight', 'value' => $talentProfile->weight, 'type' => 'number'],
        ['label' => 'Chest', 'name' => 'chest', 'value' => $talentProfile->chest, 'type' => 'number'],
        ['label' => 'Waist', 'name' => 'waist', 'value' => $talentProfile->waist, 'type' => 'number'],
        ['label' => 'Hips', 'name' => 'hips', 'value' => $talentProfile->hips, 'type' => 'number'],
        ['label' => 'Shoe size', 'name' => 'shoe_size', 'value' => $talentProfile->shoe_size, 'type' => 'number'],
    ];

    $appearanceFields = [
        ['label' => 'Skin tone', 'name' => 'skin_tone', 'value' => $talentProfile->skin_tone, 'type' => 'select', 'options' => \App\Models\TalentProfile::SKIN_TONE_SELECT],
        ['label' => 'Hair color', 'name' => 'hair_color', 'value' => $talentProfile->hair_color, 'type' => 'text', 'data-field' => 'hair_color'],
        ['label' => 'Eye color', 'name' => 'eye_color', 'value' => $talentProfile->eye_color, 'type' => 'text'],
        ['label' => 'Hijab preference', 'name' => 'hijab_preference', 'value' => $talentProfile->hijab_preference, 'type' => 'hijab', 'data-field' => 'hijab_preference'],
        ['label' => 'Visible tattoos', 'name' => 'has_visible_tattoos', 'value' => $talentProfile->has_visible_tattoos, 'type' => 'boolean'],
        ['label' => 'Piercings', 'name' => 'has_piercings', 'value' => $talentProfile->has_piercings, 'type' => 'boolean'],
    ];

    $shoots = $reviews;
    $totalShoots = $shoots->count();

    $statusPills = [
        'selected'   => ['label' => 'Completed', 'class' => 'pill-success'],
        'approved'   => ['label' => 'Completed', 'class' => 'pill-success'],
        'received'   => ['label' => 'Paid', 'class' => 'pill-success'],
        'released'   => ['label' => 'Paid', 'class' => 'pill-success'],
        'requested'  => ['label' => 'Requested', 'class' => 'pill-warning'],
        'pending'    => ['label' => 'Pending', 'class' => 'pill-warning'],
        'applied'    => ['label' => 'Applied', 'class' => 'pill-muted'],
        'shortlisted'=> ['label' => 'Shortlisted', 'class' => 'pill-muted'],
        'rejected'   => ['label' => 'Rejected', 'class' => 'pill-muted'],
    ];

    $billingEntries = $shoots->filter(function ($application) {
        return in_array($application->payment_status, ['released', 'received', 'paid', 'approved']);
    });

    $totalPaid = $billingEntries->sum(function ($application) {
        return $application->getPaymentAmount();
    });
@endphp
<div class="talent-shell">

    @can('talent_profile_delete')
        <form action="{{ route('admin.talent-profiles.destroy', $talentProfile->id) }}" method="POST" id="delete-talent-form" data-swal-confirm="Are you sure you want to delete this talent? All the data will be deleted." style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endcan

    <form action="{{ route('admin.talent-profiles.update', $talentProfile) }}" method="POST" id="talentEditForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" value="{{ $talentProfile->user_id }}">

        <div class="top-actions">
            <div class="top-actions-left">
                <a class="back-link" href="{{ route('admin.talents.dashboard') }}"><i class="fas fa-arrow-left"></i> Back to list</a>
            </div>
            <div class="top-actions-center">
            </div>
            <div class="top-actions-right">
                <div class="display-mode-only top-actions-right-display">
                    <button type="button" class="edit-btn" id="startEditBtn" style="display:none;">Edit profile</button>

                    <div class="actions-menu" id="talentActionsMenu">
                        <button type="button" class="actions-trigger" id="talentActionsBtn" aria-haspopup="true" aria-expanded="false" aria-label="Actions">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="actions-dropdown" id="talentActionsDropdown" role="menu" aria-label="Talent actions">
                            @can('talent_profile_edit')
                                <button type="button" class="actions-item" id="talentActionEdit">
                                    <i class="far fa-edit"></i> Edit
                                </button>
                            @endcan
                            @can('talent_profile_delete')
                                <button type="button" class="actions-item danger" id="talentActionDelete">
                                    <i class="far fa-trash-alt"></i> Delete
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="edit-mode-only">
                    <button type="button" class="cancel-btn" id="cancelEditBtn">Cancel</button>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </div>
        </div>

    <div class="tabs">
        <a class="tab-link active" data-tab="profile">Profile</a>
        <a class="tab-link" data-tab="reviews">Reviews & Feedback</a>
        <a class="tab-link" data-tab="shoots">Shoot History</a>
    </div>

    <div id="tab-profile" class="tab-panel active">
        <div class="section-card">
            <div class="section-title">Headshots</div>
            <div class="upload-grid">
                @foreach($headshots as $field => $label)
                    @php $img = $resolveUrl($talentProfile->{$field} ?? null); @endphp
                    <div class="upload-tile is-editable" data-field="{{ $field }}">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}" class="preview-img">
                            <button type="button" class="remove-image-btn" onclick="removeImage(this, event)" title="Remove image">
                                <i class="fa fa-times"></i>
                            </button>
                        @else
                            <div class="upload-placeholder">
                                <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px;">
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                        <div class="upload-overlay">
                            <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                            <span>{{ $img ? 'Change Photo' : 'Upload Photo' }}</span>
                        </div>
                        <input type="file" name="{{ $field }}" accept="image/*" style="display:none" onchange="previewImage(this)">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">Full-Body Shots</div>
            <div class="upload-grid">
                @foreach($fullBody as $field => $label)
                    @php $img = $resolveUrl($talentProfile->{$field} ?? null); @endphp
                    <div class="upload-tile is-editable" data-field="{{ $field }}">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}" class="preview-img">
                            <button type="button" class="remove-image-btn" onclick="removeImage(this, event)" title="Remove image">
                                <i class="fa fa-times"></i>
                            </button>
                        @else
                            <div class="upload-placeholder">
                                <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px;">
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                        <div class="upload-overlay">
                            <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                            <span>{{ $img ? 'Change Photo' : 'Upload Photo' }}</span>
                        </div>
                        <input type="file" name="{{ $field }}" accept="image/*" style="display:none" onchange="previewImage(this)">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">ID Documents</div>
            <div class="upload-grid">
                @foreach($idDocs as $field => $label)
                    @php $img = $resolveUrl($talentProfile->{$field} ?? null); @endphp
                    <div class="upload-tile is-editable" data-field="{{ $field }}">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}" class="preview-img">
                            <button type="button" class="remove-image-btn" onclick="removeImage(this, event)" title="Remove image">
                                <i class="fa fa-times"></i>
                            </button>
                        @else
                            <div class="upload-placeholder">
                                <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px;">
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                        <div class="upload-overlay">
                            <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
                            <span>{{ $img ? 'Update Document' : 'Upload Document' }}</span>
                        </div>
                        <input type="file" name="{{ $field }}" accept="image/*,application/pdf" style="display:none" onchange="previewImage(this)">
                    </div>
                @endforeach
            </div>
        </div>

        @if($muxPlaybackId)
        <div class="section-card">
            <div class="section-title">Profile Video</div>
            <div style="width: 100%; max-width: 100%; margin-top: 16px;">
                <div id="mux-player" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; background: #000;">
                    <script src="https://unpkg.com/@mux/mux-player"></script>
                    <mux-player
                        stream-type="on-demand"
                        playback-id="{{ $muxPlaybackId }}"
                        metadata-video-title="Talent Profile Video"
                        default-show-captions="false"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                    ></mux-player>
                </div>
            </div>
        </div>
        @endif

        <div class="info-grid">
            @php
                $sections = [
                    ['title' => 'Profile information', 'fields' => $profileFields],
                    ['title' => 'Account information', 'fields' => $accountFields],
                    ['title' => 'Measurements', 'fields' => $measurementFields],
                    ['title' => 'Appearance details', 'fields' => $appearanceFields],
                ];
            @endphp

            @foreach($sections as $section)
                <div class="section-card">
                    <div class="section-title">{{ $section['title'] }}</div>
                    <table class="info-table">
                        @foreach($section['fields'] as $f)
                            @if(isset($f['hide_in_edit']) && $f['hide_in_edit'])
                                <tr class="edit-mode-only" style="display: none;">
                                    <td>{{ $f['label'] }}</td>
                                    <td>
                                        <input type="hidden" name="{{ $f['name'] }}" value="{{ $f['value'] }}">
                                    </td>
                                </tr>
                            @endif
                            <tr @if(isset($f['data-field'])) data-field-row="{{ $f['data-field'] }}" @endif>
                                <td>{{ $f['label'] }}</td>
                                <td>
                                    <div class="display-mode-only {{ is_null($f['value']) || $f['value'] === '' ? 'not-set' : '' }}">
                                        @if($f['type'] === 'select' && isset($f['options']))
                                            {{ $f['options'][$f['value']] ?? $notSet }}
                                        @elseif($f['type'] === 'boolean')
                                            {{ is_null($f['value']) ? $notSet : ($f['value'] ? 'Yes' : 'No') }}
                                        @elseif($f['name'] === 'hijab_preference')
                                            @if($f['value'] === 'wear_hijab')
                                                Yes
                                            @elseif($f['value'] === 'no_hijab')
                                                No
                                            @else
                                                {{ $notSet }}
                                            @endif
                                        @elseif($f['name'] === 'nationality' && $f['value'])
                                            @php
                                                $nationalityCode = strtolower($f['value']);
                                                $countryName = $countries[$nationalityCode] ?? ucfirst($f['value']);
                                            @endphp
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <span class="fi fi-{{ $nationalityCode }}" style="width: auto; height: 18px; aspect-ratio: 4 / 3; display: inline-block;" title="{{ $countryName }}"></span>
                                                <span style="font-weight: 500;">{{ $countryName }}</span>
                                            </div>
                                        @else
                                            {{ $f['value'] ?? $notSet }}
                                        @endif
                                    </div>
                                    <div class="edit-mode-only" @if(isset($f['data-field'])) data-field="{{ $f['data-field'] }}" @endif>
                                        @if($f['type'] === 'textarea')
                                            <textarea name="{{ $f['name'] }}" class="inline-edit-input" rows="3">{{ $f['value'] }}</textarea>
                                        @elseif($f['type'] === 'select')
                                            <select name="{{ $f['name'] }}" class="inline-edit-input">
                                                <option value="">Select {{ $f['label'] }}</option>
                                                @foreach($f['options'] as $key => $label)
                                                    <option value="{{ $key }}" {{ (string)$f['value'] === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($f['type'] === 'boolean')
                                            <select name="{{ $f['name'] }}" class="inline-edit-input">
                                                <option value="1" {{ $f['value'] == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ $f['value'] == 0 ? 'selected' : '' }}>No</option>
                                            </select>
                                        @elseif($f['type'] === 'nationality')
                                            <div class="nationality-wrapper" style="display: flex; align-items: center; gap: 8px;">
                                                <span id="nationality_flag_edit" class="fi nationality-flag {{ $f['value'] ? 'fi-' . strtolower($f['value']) : '' }}" style="display: {{ $f['value'] ? 'inline-block' : 'none' }}; width: auto; height: 18px; aspect-ratio: 4 / 3;"></span>
                                                <select name="{{ $f['name'] }}" id="nationality_select" class="inline-edit-input" style="flex: 1;">
                                                    <option value="">Select nationality</option>
                                                    @foreach($countries as $code => $name)
                                                        <option value="{{ $code }}" {{ (string)$f['value'] === (string)$code ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @elseif($f['type'] === 'hijab')
                                            <select name="{{ $f['name'] }}" id="hijab_preference_select" class="inline-edit-input">
                                                <option value="">Select</option>
                                                <option value="wear_hijab" {{ $f['value'] === 'wear_hijab' ? 'selected' : '' }}>Yes</option>
                                                <option value="no_hijab" {{ $f['value'] === 'no_hijab' ? 'selected' : '' }}>No</option>
                                            </select>
                                        @else
                                            <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" value="{{ $f['value'] }}" class="inline-edit-input" {{ ($f['required'] ?? false) ? 'required' : '' }}>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($section['title'] === 'Profile information')
                            <tr>
                                <td>Labels</td>
                                <td>
                                    <div class="display-mode-only">
                                        {{ $talentProfile->labels->pluck('name')->filter()->implode(', ') ?: $notSet }}
                                    </div>
                                    <div class="edit-mode-only">
                                        <select name="labels[]" class="inline-edit-input" multiple style="height: 100px;">
                                            @foreach($labels as $label)
                                                <option value="{{ $label->id }}" {{ in_array($label->id, $talentProfile->labels->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $label->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endforeach
        </div> {{-- end info-grid --}}
    </form> {{-- end talentEditForm --}}

    <div class="action-bar display-mode-only">
        <form action="{{ route('admin.talent-profiles.reject', $talentProfile) }}" method="POST" style="margin:0;" id="reject-talent-form">
            @csrf
            <input type="hidden" name="notes" id="rejectNotesInput" value="">
            <button type="button" class="btn-reject" id="rejectTalentBtn"><i class="fas fa-times"></i> Reject</button>
        </form>
        @if(($talentProfile->verification_status ?? '') !== 'approved')
            <form action="{{ route('admin.talent-profiles.approve', $talentProfile) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-approve"><i class="fas fa-check"></i> Accept</button>
            </form>
        @endif
    </div>
</div> {{-- end tab-profile --}}

<div id="tab-reviews" class="tab-panel">
        <div class="reviews-wrap">
            <div class="reviews-card">
                <div class="overview-card">
                    <div class="overview-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <p class="overview-title">Performance Overview</p>
                        <p class="overview-sub">Overall ratings based on the talent key performance criteria</p>
                    </div>
                </div>

                <div class="review-list">
                    @forelse($reviews as $application)
                        @php
                            $project = optional($application->casting_requirement)->project_name ?? 'Untitled Project';
                            $client = optional($application->casting_requirement)->client_name ?? 'Client';
                            $date = optional($application->created_at)->format('Y-m-d') ?? '';
                            $rating = (int) ($application->rating ?? 0);
                            $isReviewed = $rating > 0;
                            $statusLabel = $isReviewed ? 'Reviewed' : 'Pending Review';
                            $actionLabel = $isReviewed ? 'View Review' : 'Add Review';
                            $statusClass = $isReviewed ? 'status-reviewed' : 'status-pending';
                        @endphp
                        <div class="review-item">
                            <div class="review-text">
                                <p class="review-title">{{ $project }}</p>
                                <div class="star-row">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star {{ $i <= $rating ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <div class="review-meta">
                                    <span>{{ $client }}</span>
                                    @if($date)
                                        <span class="meta-separator">•</span>
                                        <span>{{ $date }}</span>
                                    @endif
                                    <span class="meta-separator">•</span>
                                    <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                            </div>
                            <a class="review-action" href="{{ route('admin.casting-applications.show', $application) }}">{{ $actionLabel }}</a>
                        </div>
                    @empty
                        <div class="review-item" style="justify-content:center; text-align:center;">
                            <div class="review-text">
                                <p class="review-title" style="text-align:center;">No reviews found</p>
                                <div class="overview-sub">Once reviews are added, they will appear here.</div>
                            </div>
                        </div>
                    @endforelse
                </div>
        </div>
    </div>
</div>


    <div id="tab-shoots" class="tab-panel">
        <div class="shoots-card">
            <div class="shoot-header">
                <div>
                    <p class="shoot-title"><i class="fas fa-clipboard-list"></i>Shoot History</p>
                    <p class="shoot-sub">Previous shoots this talent has participated in</p>
                </div>
                <div class="count-box">
                    <p class="count-label">Total Shoots</p>
                    <p class="count-value">{{ $totalShoots }}</p>
                </div>
            </div>

            <div class="shoot-list">
                @forelse($shoots as $application)
                    @php
                        $req = optional($application->casting_requirement);
                        $project = $req->project_name ?? 'Untitled Project';
                        $client = $req->client_name ?? 'Client';
                        $amount = $application->getPaymentAmount();
                        $role = $req->role ?? ($application->status === 'selected' ? 'Lead Model' : 'Model');
                        $statusKey = $application->payment_status ?? $application->status ?? '';
                        $pill = $statusPills[$statusKey] ?? ['label' => 'In Progress', 'class' => 'pill-muted'];
                        $date = optional($application->created_at)->format('M d, Y') ?? '';
                        $projectNumber = $req->id ? '#'.$req->id : '#'.$application->id;
                        $ratingValue = $application->rating ?? null;
                    @endphp
                    <div class="shoot-item">
                        <div class="shoot-main">
                            <div class="shoot-top">
                                <p class="shoot-name">{{ $project }}</p>
                                <span class="pill {{ $pill['class'] }}">{{ $pill['label'] }}</span>
                            </div>
                            <p class="shoot-role">{{ $client }}</p>
                            <div class="shoot-meta">
                                @if($date)
                                    <span class="shoot-meta-item"><i class="far fa-calendar"></i> {{ $date }}</span>
                                @endif
                                @if($date && $projectNumber)
                                    <span class="meta-separator">•</span>
                                @endif
                                @if($projectNumber)
                                    <span class="shoot-meta-item">Project # {{ $projectNumber }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="shoot-amount">
                            <p class="amount-value">${{ number_format($amount, 0) }}</p>
                            <p class="amount-role">{{ $role }}</p>
                            @if(!is_null($ratingValue))
                                <span class="shoot-rating"><i class="fas fa-star"></i>{{ number_format($ratingValue, 1) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="shoot-item" style="grid-template-columns: 1fr; text-align:center;">
                        <p class="shoot-name" style="margin:0;">No shoot history available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="billing-card">
            <div class="billing-header">
                <div>
                    <p class="billing-title"><i class="fas fa-dollar-sign"></i>Billing History <span class="badge-blue">Admin Only</span></p>
                    <p class="shoot-sub">Payment records for previous shoots</p>
                </div>
                <div class="billing-total">
                    <p class="billing-label">Total Paid</p>
                    <p class="billing-value">${{ number_format($totalPaid, 0) }}</p>
                </div>
            </div>

            <div class="billing-list">
                @forelse($billingEntries as $application)
                    @php
                        $req = optional($application->casting_requirement);
                        $project = $req->project_name ?? 'Untitled Project';
                        $date = optional($application->payment_released_at ?? $application->payment_received_at ?? $application->created_at)->format('M d, Y') ?? '';
                        $method = 'Bank Transfer';
                        $amount = $application->getPaymentAmount();
                        $status = $application->payment_status ?? 'paid';
                        $statusLabel = $status === 'received' || $status === 'released' ? 'Paid' : ucfirst($status);
                    @endphp
                    <div class="billing-item">
                        <div class="billing-main">
                            <p class="billing-name">{{ $project }} - Payment</p>
                            <div class="billing-meta">
                                @if($date)
                                    <span>{{ $date }}</span>
                                @endif
                                <span>{{ $method }}</span>
                                <span class="badge-paid">{{ $statusLabel }}</span>
                            </div>
                        </div>
                        <div class="billing-amount">${{ number_format($amount, 0) }}</div>
                    </div>
                @empty
                    <div class="billing-item" style="grid-template-columns:1fr; text-align:center;">
                        <p class="billing-name" style="margin:0;">No billing history available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const tile = input.closest('.upload-tile');
            const placeholder = tile.querySelector('.upload-placeholder');
            let preview = tile.querySelector('.preview-img');
            let removeBtn = tile.querySelector('.remove-image-btn');

            reader.onload = function(e) {
                // Remove placeholder if exists
                if (placeholder) {
                    placeholder.style.display = 'none';
                }

                // Remove existing preview image if any
                if (preview && preview.tagName === 'IMG') {
                    preview.remove();
                }

                // Create new preview image
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.classList.add('preview-img');
                tile.insertBefore(newImg, tile.firstChild);

                // Add remove button if it doesn't exist
                if (!removeBtn) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'remove-image-btn';
                    btn.innerHTML = '<i class="fa fa-times"></i>';
                    btn.title = 'Remove image';
                    btn.onclick = function(e) {
                        removeImage(this, e);
                    };
                    tile.appendChild(btn);
                } else {
                    removeBtn.style.display = 'flex';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(btn, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }

        const tile = btn.closest('.upload-tile');
        const fileInput = tile.querySelector('input[type="file"]');
        const preview = tile.querySelector('.preview-img');
        const placeholder = tile.querySelector('.upload-placeholder');

        // Remove preview image
        if (preview) {
            preview.remove();
        }

        // Remove the remove button
        btn.remove();

        // Show placeholder
        if (placeholder) {
            placeholder.style.display = 'grid';
        }

        // Clear file input
        if (fileInput) {
            fileInput.value = '';
            // Reattach change handler
            fileInput.onchange = function() {
                previewImage(this);
            };
        }
    }

    function handleFileSelect(fileInput, file) {
        // Create a FileList-like object
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Call previewImage directly (don't trigger change event to avoid double processing)
        previewImage(fileInput);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching logic
        const tabs = document.querySelectorAll('.tab-link');
        const panels = document.querySelectorAll('.tab-panel');

        const setActive = (name) => {
            tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
            panels.forEach(p => p.classList.toggle('active', p.id === `tab-${name}`));
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const name = tab.dataset.tab;
                if (name) setActive(name);
            });
        });

        setActive('profile');

        // Inline edit toggle logic
        const form = document.getElementById('talentEditForm');
        const startEditBtn = document.getElementById('startEditBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        if (startEditBtn && form) {
            startEditBtn.addEventListener('click', () => {
                const shell = document.querySelector('.talent-shell');
                if (shell) {
                    shell.classList.add('is-editing');
                }
                form.classList.add('is-editing');
                // Trigger gender-based field visibility when entering edit mode
                setTimeout(toggleGenderBasedFields, 100);
            });
        }

        // Actions dropdown (3-dot)
        const actionsBtn = document.getElementById('talentActionsBtn');
        const actionsDropdown = document.getElementById('talentActionsDropdown');
        const actionEdit = document.getElementById('talentActionEdit');
        const actionDelete = document.getElementById('talentActionDelete');
        const deleteForm = document.getElementById('delete-talent-form');

        const closeActions = () => {
            if (!actionsDropdown || !actionsBtn) return;
            actionsDropdown.classList.remove('show');
            actionsBtn.setAttribute('aria-expanded', 'false');
        };
        const toggleActions = (e) => {
            e?.stopPropagation?.();
            if (!actionsDropdown || !actionsBtn) return;
            const willShow = !actionsDropdown.classList.contains('show');
            actionsDropdown.classList.toggle('show', willShow);
            actionsBtn.setAttribute('aria-expanded', willShow ? 'true' : 'false');
        };

        if (actionsBtn && actionsDropdown) {
            actionsBtn.addEventListener('click', toggleActions);
            document.addEventListener('click', (e) => {
                if (!actionsDropdown.contains(e.target) && !actionsBtn.contains(e.target)) closeActions();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeActions();
            });
        }

        if (actionEdit && startEditBtn) {
            actionEdit.addEventListener('click', (e) => {
                e.preventDefault();
                closeActions();
                startEditBtn.click();
            });
        }

        if (actionDelete && deleteForm) {
            actionDelete.addEventListener('click', (e) => {
                e.preventDefault();
                closeActions();
                if (typeof deleteForm.requestSubmit === 'function') {
                    deleteForm.requestSubmit();
                } else {
                    deleteForm.submit();
                }
            });
        }

        if (cancelEditBtn && form) {
            cancelEditBtn.addEventListener('click', () => {
                // To properly cancel, we just reload the page to discard unsaved state
                window.location.reload();
            });
        }

        // Nationality flag update
        const nationalitySelect = document.getElementById('nationality_select');
        const nationalityFlag = document.getElementById('nationality_flag_edit');

        if (nationalitySelect && nationalityFlag) {
            nationalitySelect.addEventListener('change', function() {
                const selectedValue = this.value;
                if (selectedValue && selectedValue !== '') {
                    // Remove all existing flag classes
                    nationalityFlag.className = 'fi nationality-flag';
                    // Add the new flag class
                    nationalityFlag.classList.add('fi-' + selectedValue.toLowerCase());
                    nationalityFlag.style.display = 'inline-block';
                } else {
                    nationalityFlag.style.display = 'none';
                    nationalityFlag.className = 'fi nationality-flag';
                }
            });
        }

        // Gender-based field visibility
        function toggleGenderBasedFields() {
            const genderSelect = document.querySelector('select[name="gender"]');
            if (!genderSelect) return;

            const gender = genderSelect.value;
            const hijabRow = document.querySelector('tr[data-field-row="hijab_preference"]');
            const hairColorRow = document.querySelector('tr[data-field-row="hair_color"]');
            const hijabSelect = document.getElementById('hijab_preference_select');

            // Show/hide hijab preference based on gender
            if (hijabRow) {
                if (gender === 'male') {
                    hijabRow.style.display = 'none';
                    // Clear hijab preference when hidden
                    if (hijabSelect) {
                        hijabSelect.value = '';
                    }
                } else {
                    hijabRow.style.display = '';
                }
            }

            // Show/hide hair color based on gender and hijab preference
            if (hairColorRow) {
                if (gender === 'female' && hijabSelect && hijabSelect.value === 'wear_hijab') {
                    hairColorRow.style.display = 'none';
                } else {
                    hairColorRow.style.display = '';
                }
            }
        }

        // Initialize on page load and when gender changes
        const genderSelect = document.querySelector('select[name="gender"]');
        if (genderSelect) {
            genderSelect.addEventListener('change', toggleGenderBasedFields);
            toggleGenderBasedFields(); // Initialize
        }

        // Update hair color visibility when hijab preference changes
        const hijabSelect = document.getElementById('hijab_preference_select');
        if (hijabSelect) {
            hijabSelect.addEventListener('change', toggleGenderBasedFields);
        }

        // Hide top bar success message and show SweetAlert instead
        const successMessage = document.querySelector('.alert-success');
        if (successMessage && successMessage.textContent.trim()) {
            // Hide the top bar message
            successMessage.closest('.row')?.remove();
            // Show SweetAlert with specific message
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Talent profile has been updated',
                confirmButtonColor: '#10B981',
                timer: 3000,
                timerProgressBar: true
            });
        }

        // Handle form submission to show SweetAlert on success
        const editForm = document.getElementById('talentEditForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                // Let the form submit normally
                // The success message will be handled by the redirect response
            });
        }

        // Reject flow: prompt optional notes then submit
        const rejectBtn = document.getElementById('rejectTalentBtn');
        const rejectForm = document.getElementById('reject-talent-form');
        const rejectNotesInput = document.getElementById('rejectNotesInput');

        if (rejectBtn && rejectForm && typeof Swal !== 'undefined') {
            rejectBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                const result = await Swal.fire({
                    title: 'Reject talent?',
                    text: 'Optionally add a reason (visible to admin logs / notifications).',
                    input: 'textarea',
                    inputPlaceholder: 'Add notes (optional)',
                    inputAttributes: { maxlength: 500 },
                    showCancelButton: true,
                    confirmButtonText: 'Reject',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#0f1524',
                    cancelButtonColor: '#9ca3af',
                    focusConfirm: false
                });

                if (result.isConfirmed) {
                    if (rejectNotesInput) {
                        rejectNotesInput.value = (result.value || '').trim();
                    }
                    rejectForm.submit();
                }
            });
        }

        // Prevent default drag behavior globally when dragging files
        let isDraggingFile = false;
        document.addEventListener('dragstart', function(e) {
            if (e.dataTransfer.types.includes('Files')) {
                isDraggingFile = true;
            }
        }, false);

        document.addEventListener('dragend', function(e) {
            isDraggingFile = false;
        }, false);

        // Prevent default drop behavior globally when dragging files
        document.addEventListener('dragover', function(e) {
            if (isDraggingFile) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, false);

        document.addEventListener('drop', function(e) {
            if (isDraggingFile) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, false);

        // Drag-and-drop functionality for upload tiles
        const uploadTiles = document.querySelectorAll('.upload-tile.is-editable');

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        uploadTiles.forEach(tile => {
            const fileInput = tile.querySelector('input[type="file"]');
            if (!fileInput) return;

            let dragCounter = 0;
            let isDraggingOver = false;

            // Prevent default drag behaviors on tile
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                tile.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            tile.addEventListener('dragenter', function(e) {
                dragCounter++;
                isDraggingOver = true;
                // Only highlight if in edit mode
                if (editForm && editForm.classList.contains('is-editing')) {
                    tile.classList.add('drag-over');
                }
            }, false);

            tile.addEventListener('dragover', function(e) {
                isDraggingOver = true;
                // Only highlight if in edit mode
                if (editForm && editForm.classList.contains('is-editing')) {
                    tile.classList.add('drag-over');
                }
            }, false);

            // Remove highlight when leaving drop zone
            tile.addEventListener('dragleave', function(e) {
                dragCounter--;
                if (dragCounter <= 0) {
                    dragCounter = 0;
                    isDraggingOver = false;
                    tile.classList.remove('drag-over');
                }
            }, false);

            // Handle dropped files
            tile.addEventListener('drop', function(e) {
                dragCounter = 0;
                isDraggingOver = false;
                tile.classList.remove('drag-over');

                // Only process if in edit mode
                if (!editForm || !editForm.classList.contains('is-editing')) {
                    return;
                }

                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    const file = files[0];

                    // Validate file type
                    const accept = fileInput.getAttribute('accept');
                    let isValidType = true;

                    if (accept) {
                        const acceptTypes = accept.split(',').map(t => t.trim());
                        isValidType = acceptTypes.some(type => {
                            if (type.startsWith('.')) {
                                return file.name.toLowerCase().endsWith(type.toLowerCase());
                            } else if (type.includes('*')) {
                                const baseType = type.split('/')[0];
                                return file.type.startsWith(baseType + '/');
                            } else {
                                return file.type === type;
                            }
                        });
                    }

                    if (isValidType) {
                        handleFileSelect(fileInput, file);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Please upload a file with the correct format.',
                            confirmButtonColor: '#10B981'
                        });
                    }
                }
            }, false);

            // Track if a drop just occurred to prevent click event
            let justDropped = false;

            // Click to upload handler (only when not dragging)
            tile.addEventListener('click', function(e) {
                // Don't trigger if clicking remove button
                if (e.target.closest('.remove-image-btn')) {
                    return;
                }

                // Don't trigger if we just dropped a file (prevent double action)
                if (justDropped) {
                    justDropped = false;
                    return;
                }

                // Only trigger if in edit mode
                if (editForm && editForm.classList.contains('is-editing')) {
                    fileInput.click();
                }
            }, false);

            // Mark that a drop occurred
            tile.addEventListener('drop', function() {
                justDropped = true;
                setTimeout(function() {
                    justDropped = false;
                }, 300);
            });
        });
    });
</script>
@endsection
