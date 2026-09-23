<!DOCTYPE html>
<html lang="hi" class="h-full bg-gray-50 text-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CBT Exam Engine - {{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 flex flex-col font-sans select-none">

    <!-- CBT Exam Header Bar -->
    <header class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-sm">T</div>
            <div>
                <h2 class="text-sm sm:text-base font-bold text-gray-900">{{ $title }}</h2>
                <span class="text-[11px] text-gray-500">MP Police Constable GD 2026 CBT Examination Engine</span>
            </div>
        </div>

        <!-- Live Countdown Timer -->
        <div class="flex items-center gap-4">
            <div class="px-4 py-1.5 rounded-xl bg-gray-50 border border-gray-200 text-center flex items-center gap-2">
                <i class="fa-solid fa-stopwatch text-orange-500 text-xs"></i>
                <span class="text-gray-500 text-xs">Time Left:</span>
                <span id="timer-display" class="font-mono text-sm font-black text-orange-600">00:00</span>
            </div>

            <button onclick="confirmSubmit()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-lg shadow-emerald-600/20 transition flex items-center gap-1.5">
                <i class="fa-solid fa-paper-plane"></i> Submit Test
            </button>
        </div>
    </header>

    <!-- CBT Main Layout -->
    <form id="cbt-form" action="{{ route('student.submit-test', ['type' => $type, 'id' => $id]) }}" method="POST" class="flex-grow flex flex-col md:flex-row overflow-hidden">
        @csrf
        <input type="hidden" name="time_taken_seconds" id="time_taken_seconds" value="0">

        <!-- Question View Area (Left/Middle) -->
        <div class="flex-grow p-4 sm:p-6 lg:p-8 flex flex-col justify-between overflow-y-auto">
            
            <div class="space-y-6">
                <!-- Question Number Bar -->
                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <span id="question-header-number" class="text-xs font-extrabold text-blue-600">Question 1 of {{ count($questions) }}</span>
                    <span class="text-[11px] text-gray-500 bg-white px-2.5 py-1 rounded-md border border-gray-200">Marks: +1, -0</span>
                </div>

                <!-- Questions Container -->
                @foreach($questions as $index => $q)
                    <div id="question-card-{{ $index }}" class="question-card space-y-6 {{ $index > 0 ? 'hidden' : '' }}">
                        <!-- Question Text -->
                        <div class="space-y-2">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-relaxed">
                                Q{{ $index + 1 }}. {!! nl2br(e($q->question_text_hi)) !!}
                            </h3>
                            @if($q->question_text_en)
                                <p class="text-xs text-gray-500 font-medium italic">
                                    {!! nl2br(e($q->question_text_en)) !!}
                                </p>
                            @endif
                        </div>

                        <!-- Option Choices -->
                        <div class="space-y-3 pt-2">
                            @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $optVal)
                                <label onclick="selectOption({{ $index }}, '{{ $key }}')" class="option-label-{{ $index }} option-{{ $key }} flex items-center gap-3.5 p-4 rounded-2xl bg-white border border-gray-200 hover:border-blue-300 cursor-pointer transition shadow-sm hover:shadow">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" class="hidden" onchange="markAnswered({{ $index }})">
                                    <span class="w-7 h-7 rounded-xl bg-gray-50 border border-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center shrink-0 option-badge font-mono">
                                        {{ $key }}
                                    </span>
                                    <span class="text-sm text-gray-700 font-medium">{!! e($optVal) !!}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- CBT Action Footer Controls -->
            <div class="pt-6 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3 mt-6">
                <div class="flex items-center gap-2">
                    <button type="button" onclick="clearChoice()" class="px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs border border-gray-200 transition">
                        Clear Response
                    </button>
                    <button type="button" onclick="markReview()" class="px-3.5 py-2 rounded-xl bg-purple-50 text-purple-600 font-bold text-xs border border-purple-200 hover:bg-purple-100 transition">
                        Mark for Review & Next
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" id="btn-prev" onclick="prevQuestion()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs border border-gray-200 transition">
                        ← Previous
                    </button>
                    <button type="button" id="btn-next" onclick="nextQuestion()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs shadow-lg shadow-blue-600/20 transition">
                        Save & Next →
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side Question Navigation Palette Matrix -->
        <aside class="w-full md:w-80 bg-white border-t md:border-t-0 md:border-l border-gray-200 p-4 space-y-4 shrink-0 overflow-y-auto">
            
            <!-- Legend Indicators -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-semibold text-gray-600 pb-3 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-emerald-500"></span> Answered
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-rose-500"></span> Not Answered
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-purple-500"></span> Marked Review
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-gray-100 border border-gray-200"></span> Not Visited
                </div>
            </div>

            <!-- Question Matrix Grid -->
            <div>
                <span class="text-xs font-bold text-gray-500 block mb-2">Question Navigation Matrix</span>
                <div class="grid grid-cols-5 gap-2 max-h-72 overflow-y-auto pr-1">
                    @foreach($questions as $index => $q)
                        <button type="button" id="palette-btn-{{ $index }}" onclick="jumpToQuestion({{ $index }})" class="palette-btn w-full h-9 rounded-xl bg-gray-50 border border-gray-200 text-gray-600 text-xs font-bold font-mono transition flex items-center justify-center hover:bg-gray-100">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button type="button" onclick="confirmSubmit()" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs shadow-lg transition">
                Submit Test & View Score
            </button>
        </aside>
    </form>

    <!-- JavaScript Handler for CBT Engine -->
    <script>
        let totalQuestions = {{ count($questions) }};
        let currentQuestion = 0;
        let durationMinutes = {{ $durationMinutes }};
        let totalSeconds = durationMinutes * 60;
        let secondsPassed = 0;
        let questionStates = new Array(totalQuestions).fill('not-visited'); // 'not-visited', 'answered', 'not-answered', 'review'

        questionStates[0] = 'not-answered';
        updatePalette();

        // Timer Logic
        let timerInterval = setInterval(() => {
            totalSeconds--;
            secondsPassed++;
            document.getElementById('time_taken_seconds').value = secondsPassed;

            let mins = Math.floor(totalSeconds / 60);
            let secs = totalSeconds % 60;
            document.getElementById('timer-display').innerText = 
                (mins < 10 ? '0' + mins : mins) + ':' + (secs < 10 ? '0' + secs : secs);

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                alert('Time expired! Submitting test automatically.');
                document.getElementById('cbt-form').submit();
            }
        }, 1000);

        function jumpToQuestion(index) {
            document.getElementById(`question-card-${currentQuestion}`).classList.add('hidden');
            if (questionStates[currentQuestion] === 'not-visited') {
                questionStates[currentQuestion] = 'not-answered';
            }
            currentQuestion = index;
            document.getElementById(`question-card-${currentQuestion}`).classList.remove('hidden');
            document.getElementById('question-header-number').innerText = `Question ${currentQuestion + 1} of ${totalQuestions}`;
            if (questionStates[currentQuestion] === 'not-visited') {
                questionStates[currentQuestion] = 'not-answered';
            }
            updatePalette();
        }

        function nextQuestion() {
            if (currentQuestion < totalQuestions - 1) {
                jumpToQuestion(currentQuestion + 1);
            }
        }

        function prevQuestion() {
            if (currentQuestion > 0) {
                jumpToQuestion(currentQuestion - 1);
            }
        }

        function selectOption(qIdx, optKey) {
            questionStates[qIdx] = 'answered';
            // Styling options
            let labels = document.querySelectorAll(`.option-label-${qIdx}`);
            labels.forEach(l => {
                l.classList.remove('border-blue-500', 'bg-blue-50');
                l.querySelector('.option-badge').classList.remove('bg-blue-600', 'text-white');
            });
            let target = document.querySelector(`.option-label-${qIdx}.option-${optKey}`);
            if (target) {
                target.classList.add('border-blue-500', 'bg-blue-50');
                target.querySelector('.option-badge').classList.add('bg-blue-600', 'text-white');
            }
            updatePalette();
        }

        function markAnswered(qIdx) {
            questionStates[qIdx] = 'answered';
            updatePalette();
        }

        function clearChoice() {
            let radios = document.querySelectorAll(`#question-card-${currentQuestion} input[type="radio"]`);
            radios.forEach(r => r.checked = false);
            let labels = document.querySelectorAll(`.option-label-${currentQuestion}`);
            labels.forEach(l => {
                l.classList.remove('border-blue-500', 'bg-blue-50');
                l.querySelector('.option-badge').classList.remove('bg-blue-600', 'text-white');
            });
            questionStates[currentQuestion] = 'not-answered';
            updatePalette();
        }

        function markReview() {
            questionStates[currentQuestion] = 'review';
            updatePalette();
            nextQuestion();
        }

        function updatePalette() {
            for (let i = 0; i < totalQuestions; i++) {
                let btn = document.getElementById(`palette-btn-${i}`);
                btn.className = "palette-btn w-full h-9 rounded-xl border text-xs font-bold font-mono transition flex items-center justify-center hover:opacity-90 ";
                if (i === currentQuestion) {
                    btn.classList.add('ring-2', 'ring-blue-400');
                }
                if (questionStates[i] === 'answered') {
                    btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-500');
                } else if (questionStates[i] === 'review') {
                    btn.classList.add('bg-purple-600', 'text-white', 'border-purple-500');
                } else if (questionStates[i] === 'not-answered') {
                    btn.classList.add('bg-rose-600', 'text-white', 'border-rose-500');
                } else {
                    btn.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-200');
                }
            }
        }

        function confirmSubmit() {
            if (confirm('क्या आप सचमुच अपना टेस्ट जमा (Submit) करना चाहते हैं?')) {
                document.getElementById('cbt-form').submit();
            }
        }
    </script>
</body>
</html>
