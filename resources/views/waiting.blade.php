<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK - Reviewing Product</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#0b0f19] px-4 py-6 text-white sm:flex sm:items-center sm:justify-center">
    <main class="mx-auto w-full max-w-md">
        <section class="rounded-2xl border border-[#242833] bg-[#171a21] p-5 shadow-xl sm:p-8">
            <div class="text-center">
                <p class="text-2xl font-black tracking-wide text-[#e9c38c]">INLOCK</p>
                <h1 class="mt-3 text-xl font-bold text-white sm:text-2xl">
                    We are reviewing your product
                </h1>
                <p id="status-copy" class="mt-3 text-sm leading-relaxed text-gray-400">
                    Our team is checking availability and calculating your final local price.
                </p>
            </div>

            <div class="mt-8 flex justify-center">
                <div id="loader" class="relative h-16 w-16">
                    <div class="absolute inset-0 rounded-full border-2 border-[#242833]"></div>
                    <div class="absolute inset-0 animate-spin rounded-full border-2 border-[#e9c38c] border-t-transparent"></div>
                </div>
            </div>

            <div class="mt-8">
                <x-progress-bar id="progress-bar" :value="35" label="Request progress" />
            </div>

            <div class="mt-6 flex justify-center">
                <x-status-badge id="status" :status="$request->status" />
            </div>

            <div class="mt-8 grid gap-3">
                <div id="step-review" class="rounded-lg border border-yellow-500/30 bg-yellow-500/10 p-4">
                    <p class="text-sm font-bold text-yellow-200">1. Review in progress</p>
                    <p class="mt-1 text-xs leading-relaxed text-yellow-100/80">
                        We are checking the product link, options, and availability.
                    </p>
                </div>

                <div id="step-decision" class="rounded-lg border border-[#242833] bg-[#0f1115] p-4">
                    <p class="text-sm font-bold text-gray-300">2. Pricing decision</p>
                    <p class="mt-1 text-xs leading-relaxed text-gray-500">
                        You will be redirected once the quote is ready or if the request cannot be approved.
                    </p>
                </div>
            </div>

            <x-alert type="info" class="mt-6">
                You can safely leave this page and come back from your dashboard later.
            </x-alert>
        </section>
    </main>

<script>
    const requestId = {{ $request->id }};
    let poller = null;

    function formatStatus(status) {
        return status
            .replaceAll('_', ' ')
            .replace(/\b\w/g, character => character.toUpperCase());
    }

    function badgeClasses(status) {
        const classes = {
            pending_review: ['bg-yellow-500/10', 'text-yellow-300', 'border-yellow-500/30'],
            priced: ['bg-green-500/10', 'text-green-300', 'border-green-500/30'],
            rejected: ['bg-red-500/10', 'text-red-300', 'border-red-500/30'],
            expired: ['bg-orange-500/10', 'text-orange-300', 'border-orange-500/30'],
        };

        return classes[status] || ['bg-gray-500/10', 'text-gray-300', 'border-gray-500/30'];
    }

    function setBadge(status) {
        const statusEl = document.getElementById('status');
        statusEl.className = 'inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold ';
        statusEl.classList.add(...badgeClasses(status));
        statusEl.innerText = formatStatus(status);
    }

    function setProgress(percent) {
        const bar = document.querySelector('#progress-bar div div');
        const label = document.querySelector('#progress-bar div span:last-child');

        if (bar) {
            bar.style.width = `${percent}%`;
        }

        if (label) {
            label.innerText = `${percent}%`;
        }
    }

    function redirectAfter(url) {
        window.clearInterval(poller);

        setTimeout(() => {
            window.location.href = url;
        }, 900);
    }

    function checkStatus() {
        fetch(`/request/${requestId}/status`)
            .then(response => response.json())
            .then(data => {
                const copy = document.getElementById('status-copy');
                const decision = document.getElementById('step-decision');

                setBadge(data.status);

                if (data.status === 'pending_review') {
                    setProgress(35);
                    return;
                }

                if (data.status === 'priced') {
                    setProgress(100);
                    copy.innerText = 'Your quote is ready. Redirecting you to the product page.';
                    decision.className = 'rounded-lg border border-green-500/30 bg-green-500/10 p-4';
                    redirectAfter(data.redirect_url || `/request/${requestId}/view`);
                    return;
                }

                if (data.status === 'rejected') {
                    setProgress(100);
                    copy.innerText = 'Your request has been reviewed. Redirecting you to the decision details.';
                    decision.className = 'rounded-lg border border-red-500/30 bg-red-500/10 p-4';
                    redirectAfter(data.redirect_url || `/request/${requestId}/rejected`);
                    return;
                }

                if (data.status === 'expired') {
                    setProgress(100);
                    copy.innerText = 'This quote has expired. Redirecting you to the quote details.';
                    decision.className = 'rounded-lg border border-orange-500/30 bg-orange-500/10 p-4';
                    redirectAfter(data.redirect_url || `/request/${requestId}/view`);
                }
            })
            .catch(() => {
                document.getElementById('status-copy').innerText = 'Connection issue. We will keep checking automatically.';
            });
    }

    poller = window.setInterval(checkStatus, 5000);
    checkStatus();
</script>
</body>
</html>
