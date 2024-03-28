<style>
    .header li a { font-size:12px; font-weight:600; }
    .header li { padding:10px 0px; margin-left:40px; }
    .header li.active { border-bottom: 3px solid #78BE20; }
</style>
<div class="header flex items-center justify-between">
    <div class="">
        <img src="https://mywealth.emailresources.co.uk/images/mywealth-logo-white.svg" width="160">
    </div>
    <ul class="flex">
        <li class="{{ $activeLink === 'introduction' ? 'active' : '' }}">
            <a href="#introduction">Introduction</a>
        </li>
        <li class="{{ $activeLink === 'your-objectives' ? 'active' : '' }}">
            <a href="#your-objectives">Your objectives</a>
        </li>
        <li class="{{ $activeLink === 'your-strategy' ? 'active' : '' }}">
            <a href="#your-strategy">Your strategy</a>
        </li>
        <li class="{{ $activeLink === 'next-steps' ? 'active' : '' }}">
            <a href="#next-steps">Next steps</a>
        </li>
        <li class="{{ $activeLink === 'further-information' ? 'active' : '' }}">
            <a href="#further-information">Further information</a>
        </li>
    </ul>
</div>

<!--
<div class="header">
    <img src="https://mywealth.emailresources.co.uk/images/mywealth-logo-white.svg" width="200">
    <ul class="nav">
        <li class="{{ $activeLink === 'introduction' ? 'active' : '' }}">
            <a href="#introduction">Introduction</a>
        </li>
        <li class="{{ $activeLink === 'your-objectives' ? 'active' : '' }}">
            <a href="#your-objectives">Your objectives</a>
        </li>
        <li class="{{ $activeLink === 'your-strategy' ? 'active' : '' }}">
            <a href="#your-strategy">Your strategy</a>
        </li>
        <li class="{{ $activeLink === 'next-steps' ? 'active' : '' }}">
            <a href="#next-steps">Next steps</a>
        </li>
        <li class="{{ $activeLink === 'further-information' ? 'active' : '' }}">
            <a href="#further-information">Further information</a>
        </li>
    </ul>
</div>
-->

