<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Chat') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {font-family:'Nunito', sans-serif !important;color:#858796;}
            #accordionSidebar {background-color:#4e73df;background-image:linear-gradient(180deg,#4e73df 10%,#224abe 100%);background-size:cover;overflow-anchor:none;}
            #wrapper #content-wrapper {background-color:#f8f9fc;width:100%;overflow-x:hidden;}
            #accordionSidebar li.active span,#accordionSidebar li.active i {color:#FFFFFF;font-weight:700;}
            .loader-xs {border:2px solid #f3f3f3;border-radius:50%;border-top:2px solid #3498db;width:12px;height:12px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite;}
            @-webkit-keyframes spin {0% {-webkit-transform:rotate(0deg);} 100% {-webkit-transform:rotate(360deg);}}
            @keyframes spin {0% {transform:rotate(0deg);} 100% {transform:rotate(360deg);}}
        </style>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

        @livewireStyles
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper" class="flex">
            <!-- Sidebar -->
            <ul id="accordionSidebar" class="flex flex-col pl-0 mt-0 mb-0 list-none w-[6.5rem] md:w-[14rem] min-h-screen">
                <!-- Sidebar - Brand -->
                <a class="flex justify-center items-center px-4 py-6 h-[4.375rem] text-base font-bold text-white no-underline text-center tracking-wider" href="{{ route('dashboard') }}">
                    <i class="bi bi-chat-text text-3xl"></i>
                    <div class="hidden md:inline mx-4">{{ __('Chat v1.0') }}</div>
                </a>

                <hr class="h-0 mx-4 border-0 border-t border-[rgba(255,255,255,0.15)]">

                <!-- Nav Item - Dashboard -->
                <li @class(['relative', 'active' => 'dashboard' == Route::currentRouteName()])>
                    <a class="block w-56 p-4 text-left text-[rgba(255,255,255,0.8)] hover:text-white" href="{{ route('dashboard') }}">
                        <i class="bi bi-compass-fill text-base mr-1"></i>
                        <span class="block md:inline text-xs md:text-sm">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <hr class="h-0 mx-4 mb-4 border-0 border-t border-[rgba(255,255,255,0.15)]">

                <div class="px-4 py-0 text-xs text-[rgba(255,255,255,0.4)] text-center md:text-left font-bold uppercase">{{ __('Inbox') }}</div>

                <!-- Nav Item - Message -->
                <li @class(['relative', 'active' => 'message' == Route::currentRouteName()])>
                    <a class="block w-56 p-4 text-left text-[rgba(255,255,255,0.8)] hover:text-white" href="{{ route('message') }}">
                        <i class="bi bi-chat-fill text-base mr-1"></i>
                        <span class="block md:inline text-xs md:text-sm">{{ __('Message') }}</span>
                    </a>
                </li>

                <hr class="h-0 mx-4 mb-0 border-0 border-t border-[rgba(255,255,255,0.15)]">

                <!-- Nav Item - Template -->
                <li @class(['relative', 'active' => 'template' == Route::currentRouteName()])>
                    <a class="block w-56 p-4 text-left text-[rgba(255,255,255,0.8)] hover:text-white" href="{{ route('template') }}">
                        <i class="bi bi-chat-left-text-fill text-base mr-1"></i>
                        <span class="block md:inline text-xs md:text-sm">{{ __('Template') }}</span>
                    </a>
                </li>

                <hr class="h-0 mx-4 mb-4 border-0 border-t border-[rgba(255,255,255,0.15)]">

                @if (auth()->user()->role < 2)
                <div class="px-4 py-0 text-xs text-[rgba(255,255,255,0.4)] text-center md:text-left font-bold uppercase">{{ __('User') }}</div>

                <!-- Nav Item - Staff -->
                <li @class(['relative', 'active' => 'user' == Route::currentRouteName()])>
                    <a class="block w-56 p-4 text-left text-[rgba(255,255,255,0.8)] hover:text-white" href="{{ route('user') }}">
                        <i class="bi bi-people-fill text-base mr-1"></i>
                        <span class="block md:inline text-xs md:text-sm">{{ __('Staff') }}</span>
                    </a>
                </li>

                <hr class="h-0 mx-4 mb-4 border-0 border-t border-[rgba(255,255,255,0.15)]">
                @endif

                <div class="px-4 py-0 text-xs text-[rgba(255,255,255,0.4)] text-center md:text-left font-bold uppercase">{{ __('Marketplace') }}</div>

                <!-- Nav Item - Shop -->
                <li @class(['relative', 'active' => 'shop' == Route::currentRouteName()])>
                    <a class="block w-56 p-4 text-left text-[rgba(255,255,255,0.8)] hover:text-white" href="{{ route('shop') }}">
                        <i class="bi bi-bucket-fill text-base mr-1"></i>
                        <span class="block md:inline text-xs md:text-sm">{{ __('Shop') }}</span>
                    </a>
                </li>

                <hr class="h-0 mx-4 mb-4 border-0 border-t border-[rgba(255,255,255,0.15)]">
            </ul>
            <!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="flex flex-col">
                <!-- Main Content -->
                <div id="content" class="grow shrink-0 basis-auto">
                    <!-- Topbar -->
                    <nav class="flex relative items-center py-2 px-4 flex-row flex-nowrap bg-white h-[4.375rem] mb-6 shadow-[0_4px_42px_0_rgba(58,59,69,.15)]">
                        <!-- Topbar Navbar -->
                        <ul class="flex flex-row p-0 m-0 ml-auto list-none">
                            <div class="hidden sm:block w-0 h-9 my-auto mx-4 border-r border-[#e3e6f0]"></div>

                            <!-- Nav Item - User Information -->
                            <li class="static sm:relative" x-data="{ open: false }">
                                <a @click.prevent="open = ! open" class="flex h-[4.375rem] cursor-pointer whitespace-nowrap bg-white text-[#7a7e82] pl-2.5 pr-3 align-middle items-center text-sm" href="#" id="userDropdown">
                                    <span class="mr-2 hidden lg:inline text-gray-400 text-xs">{{ auth()->user()->name }}</span>
                                    <img class="align-middle border-none w-8 h-8 rounded-full" src="/images/icons/undraw_profile.svg">
                                </a>
                                <div x-show="open" x-transition:enter.duration.500ms x-transition:leave.duration.400ms class="absolute right-0 mt-0.5 px-0 py-2 bg-clip-padding left-auto top-full z-[1000] min-w-[10rem] text-sm text-left text-[#858796] bg-white border border-solid border-[#e3e6f0] rounded-md shadow-[0_0.15rem_1.75rem_0_rgba(58,59,69,.15)] list-none" style="display: none;">
                                    <a @click.prevent="Livewire.dispatch('openModal', { component: 'edit-profile' })" class="w-full inline-block py-2 pl-4 pr-6 text-[#454444] align-middle" href="#">
                                        <i class="bi bi-person w-5 mr-2 text-sm text-gray-400 text-center font-bold align-bottom"></i>
                                        {{ __('My Profile') }}
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block" x-ref="logout">
                                        @csrf
                                        <a @click.prevent="$refs.logout.submit()" class="w-full inline-block py-2 pl-4 pr-6 text-[#454444] align-middle" href="#">
                                            <i class="bi bi-box-arrow-right w-5 mr-2 text-sm text-gray-400 text-center font-bold align-bottom"></i>
                                            {{ __('Logout') }}
                                        </a>
                                    </form>
                                </div>
                            </li>
                            <!-- End of Nav Item - User Information -->
                        </ul>
                        <!-- End of Topbar Navbar -->
                    </nav>
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="w-full mx-auto px-6">
                        <!-- Page Heading -->
                        <div class="flex items-center justify-between mb-6">
                            {{ $title }}
                            {{ $button }}
                        </div>

                        @if (session()->has('error'))
                        <div class="relative mb-4 px-5 py-3 border border-solid border-transparent rounded-md text-[#78261f] bg-[#fadbd8] border-[#f8ccc8]" role="alert">{{ session('error') }}</div>
                        @endif

                        @if (session()->has('message'))
                        <div class="relative mb-4 px-5 py-3 border border-solid border-transparent rounded-md text-[#0f6848] bg-[#d2f4e8] border-[#bff0de]" role="alert">{{ session('message') }}</div>
                        @endif

                        <!-- Content Row -->
                        <div class="flex flex-wrap">
                            <div class="w-full basis-0 grow">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                    <!-- End of Begin Page Content -->
                </div>
                <!-- End of Main Content -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        @livewireScripts
        @livewire('wire-elements-modal')

        <script type="text/javascript">
            addEventListener('DOMContentLoaded', () => {
                Livewire.on('multiSelectWithoutCtrl', (event) => {
                    event.preventDefault();
                    event.target.parentElement.focus();
                    event.target.selected = ! event.target.selected;
                });
            });
        </script>
    </body>
</html>
