<style>
    .page-number {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        padding: 6px 10px 10px 10px;
        border-width: 1px;
        border-color:rgba(231, 54, 213, 0.5);
        border-style: solid;
        color: #FFFFFF;
        text-align: center;
        vertical-align: middle;
        font-size: 10px !important;
        position: absolute;
        bottom: 8mm;
        left: 16mm;
        content: counter(page);
    }

    .previous-next-links {
        position: absolute;
        bottom: 8mm;
        right: 16mm;
    }

    .previous-next-links a {
        color: #FFFFFF;
        text-decoration: none;
        font-size: 28px;
        display: inline-block
    }

    .previous-next-links a:last-of-type {
        margin-left: 32px;
    }
</style>

<div class="page-number"></div>

<div class="previous-next-links">
    <a href="{{ $previousLink }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 19 34" fill="none">
            <path d="M18 1L2 17L18 33" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </a>
    <a href="{{ $nextLink }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="23" viewBox="0 0 19 34" fill="none">
            <path d="M1.5 33L17.5 17L1.5 1" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </a>
</div>
