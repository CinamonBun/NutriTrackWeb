<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4Ever Young - Classified</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #050505;
            color: #e5e5e5;
            overflow-x: hidden;
        }

        .font-code {
            font-family: 'Space Mono', monospace;
        }

        /* Matrix Background Effect */
        .matrix-bg {
            background-image: radial-gradient(#112211 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Glitch Effect pada Hover Avatar */
        .avatar-glitch:hover {
            filter: drop-shadow(-2px 2px 0px rgba(61, 204, 199, 0.7)) drop-shadow(2px -2px 0px rgba(255, 0, 255, 0.7));
            transform: scale(1.05);
            transition: all 0.2s ease;
        }

        /* Terminal Typing Cursor */
        .cursor::after {
            content: '|';
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="selection:bg-[#3dccc7] selection:text-black matrix-bg">

    <nav class="fixed top-0 w-full z-50 px-6 py-6 flex justify-between items-center backdrop-blur-md bg-black/50 border-b border-[#3dccc7]/20">
        <a href="index.php" class="text-sm font-code font-bold flex items-center gap-2 hover:text-[#3dccc7] transition">
            <i class="fas fa-chevron-left text-xs"></i> return_to_main();
        </a>
        <div class="font-code text-xs text-[#3dccc7] animate-pulse">
            [CONNECTED_SECURELY]
        </div>
    </nav>

    <main>
        <section class="min-h-[70vh] flex flex-col items-center justify-center px-4 text-left pt-20 relative">

            <div class="absolute top-1/3 left-10 opacity-20 hidden md:block text-left font-code text-xs text-[#3dccc7]">
                &lt;div class="secret-layer"&gt;<br>
                &nbsp;&nbsp;hidden: true;<br>
                &nbsp;&nbsp;power: 9000;<br>
                &lt;/div&gt;
            </div>

            <div class="relative z-10 max-w-4xl">
                <p class="text-xl md:text-2xl text-gray-400 leading-relaxed font-code mb-8">
                    4Ever Young is an applied research lab working on the future of programming. We are a group of researchers, engineers, and technologists inventing at the edge of what's useful and possible.
                </p>

                <p class="text-xl md:text-2xl text-gray-400 leading-relaxed font-code mb-8">
                    We have much to learn, try, and build.
                </p>

                <p class="text-xl md:text-2xl text-gray-400 leading-relaxed font-code mb-8">
                    You don't need to know our faces to trust our code. We build the logic that keeps you healthy.
                </p>
            </div>
        </section>

        <section class="py-10 bg-black font-code text-xs text-gray-600 border-t border-[#3dccc7]/10">
            <div class="max-w-4xl mx-auto px-6">
                <div class="flex flex-col gap-2">
                    <p>> Initiating system shutdown...</p>
                    <p>> 4Ever Young Collective © 2025</p>
                    <p class="text-[#3dccc7]">> Status: Online & Watching.</p>
                </div>
            </div>
        </section>

    </main>
</body>

</html>