{{--
    INLOCK application logo.

    Replaces the default Laravel polyhedron SVG.
    Classes (size, colour) are injected by the caller via {{ $attributes }}:
      - layouts/guest.blade.php          → w-16 h-16 fill-current text-[#e9c38c]
      - layouts/navigation.blade.php     → block h-9 w-auto fill-current text-[#e9c38c]

    The hexagon path matches the inline SVG on welcome.blade.php so the
    logo is identical across the landing page, auth pages, and the app nav.
--}}
<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <path d="M50 5 L90 25 L90 75 L50 95 L10 75 L10 25 Z"/>
</svg>
