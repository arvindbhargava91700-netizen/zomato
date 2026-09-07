@php
    $steps = [
        'account' => ['label' => 'Account', 'icon' => 'user.svg', 'activeIcon' => 'user.svg', 'route' => 'checkout'],
        'address' => ['label' => 'Address', 'icon' => 'location.svg', 'activeIcon' => 'location-active.svg', 'route' => 'address'],
        'payment' => ['label' => 'Payment', 'icon' => 'wallet-add.svg', 'activeIcon' => 'wallet-add-active.svg', 'route' => 'payment'],
        'confirm' => ['label' => 'Confirm', 'icon' => 'verify.svg', 'activeIcon' => 'verify-active.svg', 'route' => 'confirmOrder'],
    ];
    $order = array_keys($steps);
    $currentIndex = array_search($step, $order);
@endphp
<div class="process-section">
    <ul class="process-list">
        @foreach ($steps as $key => $s)
            @php
                $idx = array_search($key, $order);
                $state = $idx < $currentIndex ? 'done' : ($key === $step ? 'active' : '');
                $icon = ($key === $step || $state === 'done') ? $s['activeIcon'] : $s['icon'];
            @endphp
            <li class="{{ $state }}">
                <a>
                    <div class="process-icon">
                        <img class="img-fluid icon" src="front/assets/images/svg/{{ $icon }}"
                            alt="{{ $s['label'] }}">
                    </div>
                    <h6>{{ $s['label'] }}</h6>
                </a>
            </li>
        @endforeach
    </ul>
</div>
