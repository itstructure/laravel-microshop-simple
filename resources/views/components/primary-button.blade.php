<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary bg-gradient']) }}>
    {{ $slot }}
</button>
