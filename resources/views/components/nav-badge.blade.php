@props(['count' => 0])
@if((int) $count > 0)
<span style="
    position: absolute;
    top: 1px; right: 1px;
    min-width: 16px; height: 16px;
    background: #EF4444;
    color: white;
    font-size: .58rem;
    font-weight: 900;
    border-radius: 99px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid white;
    pointer-events: none;
    z-index: 10;
    line-height: 1;
    transform: translate(50%, -50%);
    box-shadow: 0 1px 4px rgba(239,68,68,.45);
    animation: nb-pop .2s cubic-bezier(.34,1.56,.64,1);
">{{ (int) $count > 99 ? '99+' : (int) $count }}</span>
@endif

<style>
@keyframes nb-pop {
    from { transform: translate(50%,-50%) scale(0); }
    to   { transform: translate(50%,-50%) scale(1); }
}
</style>
