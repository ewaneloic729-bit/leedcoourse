<style>
    .global-brand-badge {
        position: fixed;
        top: 14px;
        left: 14px;
        z-index: 9999;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(2,6,23,0.74);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.26);
        box-shadow: 0 10px 30px rgba(2,6,23,0.28);
    }
    .global-brand-logo {
        width: clamp(64px, 10vw, 156px);
        height: clamp(64px, 10vw, 156px);
        display: block;
        border-radius: clamp(10px, 2.5vw, 18px);
        object-fit: contain;
    }
</style>
<a href="{{ url('/') }}" aria-label="Accueil LEEDCOURSE" class="global-brand-badge">
    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo LEEDCOURSE" class="global-brand-logo">
    <span style="letter-spacing:.12em;font-weight:800;background:linear-gradient(90deg,#ffffff 15%,#86efac 40%,#22c55e 80%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-shadow:0 8px 30px rgba(34,197,94,.28);font-size:14px;line-height:1;">LEEDCOURSE</span>
</a>
