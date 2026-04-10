<button {{ $attributes->merge(['type' => 'submit', 'class' => 'pro-submit inline-flex items-center px-4 py-2 bg-emerald-600 border border-emerald-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:border-emerald-700 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
