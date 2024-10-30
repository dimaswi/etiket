<!-- This form uses the fabform.io form backend service -->
<!-- Signup on fabform.io to get your personal form id to save form submissions -->

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Klinik Rawat Inap Utama Muhammadiyah Kedungadem</title>
    {!! htmlScriptTagJsApi() !!}
</head>

<body>
    <div class="isolate bg-white px-6 py-12 sm:py-18 lg:px-2">
        <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
            aria-hidden="true">
            <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="flex justify-center">
            <img src="{{ url('images/logo.png') }}" class="h-16 w-16" alt="">
        </div>
        <div class="mx-auto max-w-2xl text-center">

            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Saran Dan Masukan</h2>
            <p class="mt-2 text-lg leading-8 text-gray-600">Saran dan masukan anda sangat berarti untuk pengembangan
                kami.</p>
        </div>
        <form action="{{ url('/kirim_saran') }}" method="post" class="mx-auto mt-16 max-w-xl sm:mt-20">
            @session('success')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"> {{ $value }}</span>
                </div>
            @endsession

            @if ($errors->has('g-recaptcha-response'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"> {{ $errors->first('g-recaptcha-response') }}</span>
                </div>
            @endif

            @session('error')
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"> {{ $value }}</span>
                </div>
            @endsession

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                @csrf
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-sm font-semibold leading-6 text-gray-900">Nama</label>
                    <div class="mt-2.5">
                        <input type="text" name="nama" id="nama" placeholder="Masukan Nama" required
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">Email</label>
                    <div class="mt-2.5">
                        <input type="email" name="email" id="email" placeholder="Masukan Email" required
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="nomor" class="block text-sm font-semibold leading-6 text-gray-900">Nomor</label>
                    <div class="mt-2.5">
                        <input type="text" name="nomor" id="nomor" placeholder="Masukan Nomor" required
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="pesan" class="block text-sm font-semibold leading-6 text-gray-900">Pesan</label>
                    <div class="mt-2.5">
                        <textarea name="pesan" id="pesan" rows="6" placeholder="Masukan Pesan Anda" required
                            class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    {!! htmlFormSnippet() !!}
                </div>

            </div>
            <div class="mt-10">
                <button type="submit"
                    class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Kirim
                    Saran</button>
            </div>
        </form>
    </div>
</body>

</html>
