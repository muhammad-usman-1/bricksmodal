<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>model dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white">

    <!-- Responsive Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-5 lg:gap-0 min-h-screen">

        <!-- Sidebar - Hidden on mobile -->
        <div class="hidden lg:block lg:col-span-2">
            <div class="flex-shrink-0 ml-10 mb-10 mt-2">
                <img src="{{ asset('images/logo.png') }}" class="w-36 md:w-44" alt="BRICKS Model" />
            </div>

            <!-- Dashboard -->
            <div
                class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors bg-[#E9D8D3]">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M3.99999 10L12 3L20 10L20 20H15V16C15 15.2044 14.6839 14.4413 14.1213 13.8787C13.5587 13.3161 12.7956 13 12 13C11.2043 13 10.4413 13.3161 9.87868 13.8787C9.31607 14.4413 9 15.2043 9 16V20H4L3.99999 10Z"
                        stroke="#8F6668" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h1 class="text-xl text-[#8F6668]">Dashboard</h1>
            </div>

            <!-- Project -->
            <div
                class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors hover:bg-[#F4F0EF]">
                <svg fill="#8F6668" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 452.392 452.393"
                    xml:space="preserve">
                    <g>
                        <g id="Layer_8_23_">
                            <path d="M406.657,149.964H122.883l271.078-78.849c5.842-1.699,9.224-7.841,7.517-13.686L387.092,7.957
			c-1.694-5.844-7.834-9.215-13.68-7.518L42.666,96.647c-5.844,1.699-9.217,7.839-7.52,13.685l14.39,49.47
			c0.353,1.212,0.936,2.293,1.632,3.267v49.456c0,6.086,4.951,11.04,11.042,11.04h7.356v219.999c0,4.879,3.962,8.829,8.832,8.829
			h312.062c4.87,0,8.83-3.955,8.83-8.829V223.565h7.354c6.085,0,11.043-4.954,11.043-11.04v-51.521
			C417.699,154.916,412.741,149.964,406.657,149.964z M259.985,154.38h35.337l-35.224,64.769h-35.33L259.985,154.38z
			 M164.308,154.38h35.333l-35.226,64.769h-35.331L164.308,154.38z M321.548,20.125l51.907,52.35l-33.913,9.87l-51.92-52.354
			L321.548,20.125z M233.915,45.613l51.915,52.353l-33.923,9.866l-51.924-52.351L233.915,45.613z M47.035,135.395l-7.647-26.296
			c-1.021-3.508,1.008-7.19,4.51-8.208l9.805-2.848l51.489,51.927H62.21c-0.225,0-0.439,0.057-0.655,0.068L47.035,135.395z
			 M91.889,154.38l-20.194,5.877l-1.665-1.674l2.282-4.203H91.889z M72.419,219.149H62.214c-3.653,0-6.629-2.97-6.629-6.624v-27.39
			l10.083-18.528l41.961-12.21L72.419,219.149z M142.034,72.341l51.916,52.351l-33.923,9.869L108.11,82.207L142.034,72.341z
			 M356.75,347.402H110.937v-14.244H356.75V347.402z M356.75,287.045H110.937v-14.237H356.75V287.045z M351.36,219.149h-35.331
			l35.216-64.769h35.339L351.36,219.149z" />
                        </g>
                    </g>
                </svg>
                <h1 class="text-xl text-[#8F6668]">Project</h1>
            </div>

            <!-- Payments -->
            <div
                class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors hover:bg-[#F4F0EF]">
                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#8F6668" stroke-width="1.5" />
                    <path d="M12 6V18" stroke="#8F6668" stroke-width="1.5" stroke-linecap="round" />
                    <path
                        d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                        stroke="#8F6668" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <h1 class="text-xl text-[#8F6668]">Payments</h1>
            </div>

            <!-- Logout -->
            <div
                class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer hover:bg-red-50 text-red-600 transition-colors">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M15 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H15M8 8L4 12M4 12L8 16M4 12L16 12"
                        stroke="#8F6668" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h1 class="text-xl">Logout</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-span-1 lg:col-span-10 px-2 sm:px-4 md:px-5">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-2xl md:text-4xl font-bold text-[#8F6668] text-center sm:text-left">Dashboard</h1>
                <div class="flex items-center gap-4">
                    <svg fill="#8F6668" width="20px" height="20px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" id="filter-alt" class="icon glyph">
                        <path d="M12,9a3.66,3.66,0,0,0,1-.13V21a1,1,0,0,1-2,0V8.87A3.66,3.66,0,0,0,12,9Z"></path>
                        <path d="M19,16a3.66,3.66,0,0,0,1-.13V21a1,1,0,0,1-2,0V15.87A3.66,3.66,0,0,0,19,16Z"></path>
                        <path d="M20,3V8.13a3.91,3.91,0,0,0-2,0V3a1,1,0,0,1,2,0Z"></path>
                        <path d="M6,3V15.13A3.66,3.66,0,0,0,5,15a3.66,3.66,0,0,0-1,.13V3A1,1,0,0,1,6,3Z"></path>
                        <path d="M8,19a3,3,0,1,1-4-2.82,2.87,2.87,0,0,1,2,0A3,3,0,0,1,8,19Z"></path>
                        <path d="M15,5a3,3,0,0,1-2,2.82,2.87,2.87,0,0,1-2,0A3,3,0,1,1,15,5Z"></path>
                        <path
                            d="M22,12a3,3,0,0,1-2,2.82,2.87,2.87,0,0,1-2,0,3,3,0,0,1,0-5.64,2.87,2.87,0,0,1,2,0A3,3,0,0,1,22,12Z">
                        </path>
                    </svg>
                    <svg fill="#8F6668" width="20px" height="20px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12,23a2,2,0,0,1-2-2h4A2,2,0,0,1,12,23ZM20,6a2,2,0,1,0-2,2A2,2,0,0,0,20,6Zm.707,11.293L19,15.586V10H17v6a1,1,0,0,0,.293.707l.293.293H6.414l.293-.293A1,1,0,0,0,7,16V10a4.98,4.98,0,0,1,5.912-4.912L14.5,3.5a.913.913,0,0,0-.168-.1A7,7,0,0,0,13,3.084V2a1,1,0,0,0-2,0V3.08A7,7,0,0,0,5,10v5.586L3.293,17.293A1,1,0,0,0,4,19H20a1,1,0,0,0,.707-1.707Z" />
                    </svg>
                    <img src="{{ asset('images/model1.jpg') }}" class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-full" alt="">
                </div>
            </div>

            <!-- Tabs -->
            <div class="font-medium text-lg flex gap-10 my-5 flex-wrap">
                <p class="text-[#8F6668] border-b-2 border-[#8F6668] pb-1 cursor-pointer">All</p>
                <p class="text-gray-400 hover:text-[#8F6668] cursor-pointer">Applied</p>
                <p class="text-gray-400 hover:text-[#8F6668] cursor-pointer">Shortlisted</p>
                <p class="text-gray-400 hover:text-[#8F6668] cursor-pointer">Selected</p>
                <p class="text-gray-400 hover:text-[#8F6668] cursor-pointer">Rejected</p>
            </div>

            <!-- Search -->
            <div class="flex items-center w-full my-5">
                <div class="relative w-full">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <path
                            d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                            stroke="#8F6668" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" placeholder="Search here..."
                        class="w-full bg-[#F4F0EF] border border-[#8F6668] placeholder:text-[#BEA7A8] rounded-lg pl-10 pr-4 py-2 shadow-md focus:outline-none focus:ring-1 focus:ring-[#8F6668] transition duration-200" />
                    <button
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#8F6668] text-white px-4 py-1 rounded-lg hover:bg-[#E9D8D3] hover:text-[#8F6668] transition duration-200 text-sm">
                        Search
                    </button>
                </div>
            </div>

            <!-- Example Card -->
            <div class="bg-[#F4F0EF] border border-[#8F6668] p-5 rounded-lg shadow-md mb-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
                        <h1 class="text-[#8F6668] text-lg font-semibold">Project Commercial Shoot</h1>
                        <div class="flex items-center bg-[#8F6668] text-white text-xs py-1 px-2 gap-2 rounded-full">
                            <svg width="15px" height="15px" viewBox="-4 0 32 32" version="1.1" sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#ffffff">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">
                                    </path>
                                </g>
                            </svg>
                            <span>Kuwait City</span>
                        </div>
                    </div>
                    <h1 class="text-[#BB7800] text-sm font-semibold bg-[#F7E6C7] px-2 py-1 rounded-full w-fit">
                        Shortlisted</h1>
                </div>

                <h1 class="text-[#8F6668] text-lg font-bold mt-2">Ingest Burger Shoot</h1>
                <div class="flex flex-col sm:flex-row gap-2 text-sm">
                    <span class="text-[#8F6668]">Date: December 20 2025-22,</span>
                    <span class="text-[#8F6668]">11:00am-7:00pm</span>
                </div>

                <div class="my-4 flex gap-2 flex-wrap">
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Female</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">5'8" +</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Any Ethnicity</span>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex gap-2 items-center">
                        <span class="text-[#8f6668] text-sm">Rate:</span>
                        <span class="text-[#8f6668] text-2xl font-bold">250KD</span>
                        <span class="text-[#8f6668] text-sm">/day</span>
                    </div>
                    <button
                        class="bg-[#8F6668] text-white px-4 py-1 rounded-lg hover:bg-[#E9D8D3] hover:text-[#8F6668] transition duration-200 text-sm w-full sm:w-auto">
                        View Details
                    </button>
                </div>
            </div>
            <div class="bg-[#F4F0EF] border border-[#8F6668] p-5 rounded-lg shadow-md mb-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
                        <h1 class="text-[#8F6668] text-lg font-semibold">Project Commercial Shoot</h1>
                        <div class="flex items-center bg-[#8F6668] text-white text-xs py-1 px-2 gap-2 rounded-full">
                            <svg width="15px" height="15px" viewBox="-4 0 32 32" version="1.1" sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#ffffff">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">
                                    </path>
                                </g>
                            </svg>
                            <span>Kuwait City</span>
                        </div>
                    </div>
                    <h1 class="text-[#029C66] text-sm font-semibold bg-[#CDEDD7] px-2 py-1 rounded-full w-fit">
                        Selected</h1>
                </div>

                <h1 class="text-[#8F6668] text-lg font-bold mt-2">Ingest Burger Shoot</h1>
                <div class="flex flex-col sm:flex-row gap-2 text-sm">
                    <span class="text-[#8F6668]">Date: December 20 2025-22,</span>
                    <span class="text-[#8F6668]">11:00am-7:00pm</span>
                </div>

                <div class="my-4 flex gap-2 flex-wrap">
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Female</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">5'8" +</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Any Ethnicity</span>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex gap-2 items-center">
                        <span class="text-[#8f6668] text-sm">Rate:</span>
                        <span class="text-[#8f6668] text-2xl font-bold">250KD</span>
                        <span class="text-[#8f6668] text-sm">/day</span>
                    </div>
                    <button
                        class="bg-[#8F6668] text-white px-4 py-1 rounded-lg hover:bg-[#E9D8D3] hover:text-[#8F6668] transition duration-200 text-sm w-full sm:w-auto">
                        View Details
                    </button>
                </div>
            </div>
            <div class="bg-[#F4F0EF] border border-[#8F6668] p-5 rounded-lg shadow-md mb-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
                        <h1 class="text-[#8F6668] text-lg font-semibold">Project Commercial Shoot</h1>
                        <div class="flex items-center bg-[#8F6668] text-white text-xs py-1 px-2 gap-2 rounded-full">
                            <svg width="15px" height="15px" viewBox="-4 0 32 32" version="1.1" sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#ffffff">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">
                                    </path>
                                </g>
                            </svg>
                            <span>Kuwait City</span>
                        </div>
                    </div>
                    <h1 class="text-[#C10000] text-sm font-semibold bg-[#FFBBBB] px-2 py-1 rounded-full w-fit">
                        Rejected</h1>
                </div>

                <h1 class="text-[#8F6668] text-lg font-bold mt-2">Ingest Burger Shoot</h1>
                <div class="flex flex-col sm:flex-row gap-2 text-sm">
                    <span class="text-[#8F6668]">Date: December 20 2025-22,</span>
                    <span class="text-[#8F6668]">11:00am-7:00pm</span>
                </div>

                <div class="my-4 flex gap-2 flex-wrap">
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Female</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">5'8" +</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Any Ethnicity</span>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex gap-2 items-center">
                        <span class="text-[#8f6668] text-sm">Rate:</span>
                        <span class="text-[#8f6668] text-2xl font-bold">250KD</span>
                        <span class="text-[#8f6668] text-sm">/day</span>
                    </div>
                    <button
                        class="bg-[#8F6668] text-white px-4 py-1 rounded-lg hover:bg-[#E9D8D3] hover:text-[#8F6668] transition duration-200 text-sm w-full sm:w-auto">
                        View Details
                    </button>
                </div>
            </div>
            <div class="bg-[#F4F0EF] border border-[#8F6668] p-5 rounded-lg shadow-md mb-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
                        <h1 class="text-[#8F6668] text-lg font-semibold">Project Commercial Shoot</h1>
                        <div class="flex items-center bg-[#8F6668] text-white text-xs py-1 px-2 gap-2 rounded-full">
                            <svg width="15px" height="15px" viewBox="-4 0 32 32" version="1.1" sketch:type="MSPage">
                                <g id="Icon-Set" sketch:type="MSLayerGroup"
                                    transform="translate(-104.000000, -411.000000)" fill="#ffffff">
                                    <path
                                        d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z"
                                        id="location" sketch:type="MSShapeGroup">
                                    </path>
                                </g>
                            </svg>
                            <span>Kuwait City</span>
                        </div>
                    </div>
                    <h1 class="text-[#848A90] text-sm font-semibold bg-[#DBDDDE] px-2 py-1 rounded-full w-fit">
                        Applied</h1>
                </div>

                <h1 class="text-[#8F6668] text-lg font-bold mt-2">Ingest Burger Shoot</h1>
                <div class="flex flex-col sm:flex-row gap-2 text-sm">
                    <span class="text-[#8F6668]">Date: December 20 2025-22,</span>
                    <span class="text-[#8F6668]">11:00am-7:00pm</span>
                </div>

                <div class="my-4 flex gap-2 flex-wrap">
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Female</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">5'8" +</span>
                    <span class="bg-[#8F6668] text-white text-xs py-1 px-2 rounded-full">Any Ethnicity</span>
                </div>

                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex gap-2 items-center">
                        <span class="text-[#8f6668] text-sm">Rate:</span>
                        <span class="text-[#8f6668] text-2xl font-bold">250KD</span>
                        <span class="text-[#8f6668] text-sm">/day</span>
                    </div>
                    <button
                        class="bg-[#8F6668] text-white px-4 py-1 rounded-lg hover:bg-[#E9D8D3] hover:text-[#8F6668] transition duration-200 text-sm w-full sm:w-auto">
                        View Details
                    </button>
                </div>
            </div>

        </div>
    </div>

</body>

</html>