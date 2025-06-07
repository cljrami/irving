<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>THE WHOLE MAN QUIZ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            background: linear-gradient(120deg, #232526, #414345);
            color: #fff;
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .quiz-container {
            max-width: 500px;
            margin: 40px auto;
            background: rgba(30, 30, 40, 0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 32px 24px 24px 24px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #333;
            border-radius: 4px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: linear-gradient(90deg, #ff512f, #dd2476);
            width: 0%;
            transition: width 0.4s cubic-bezier(0.4, 2, 0.6, 1);
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 18px;
        }

        .question-section {
            min-height: 120px;
            margin-bottom: 18px;
            animation: fadeIn 0.7s;
        }

        .answers {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .answer-btn {
            background: #232526;
            color: #fff;
            border: 2px solid #ff512f;
            border-radius: 8px;
            padding: 14px 10px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border 0.2s;
        }

        .answer-btn:hover,
        .answer-btn.selected {
            background: #ff512f;
            color: #fff;
            border-color: #fff;
        }

        .nav-btns {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
        }

        .nav-btn {
            background: #dd2476;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }

        .nav-btn:disabled {
            background: #555;
            cursor: not-allowed;
        }

        .intro,
        .outro {
            text-align: center;
            animation: fadeIn 1s;
        }

        .outro {
            color: #ff512f;
            font-size: 1.2em;
        }

        .status-info {
            text-align: center;
            font-size: 0.9em;
            color: #aaa;
            margin-bottom: 12px;
        }

        @media (max-width: 600px) {
            .quiz-container {
                padding: 18px 6px 18px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="quiz-container" id="quizContainer">
        <!-- Content is dynamically generated -->
    </div>
    <script>
        const quiz = [{
                section: "INTRO",
                title: "THE WHOLE MAN QUIZ",
                text: "Most men live disconnected from their full masculine power—physically, emotionally, and sexually—without even realizing it.<br><br>This quiz will help you assess how whole and connected you really are... and show you exactly where to begin your restoration journey.<br><br><b>It only takes 3 minutes. Be brutally honest with yourself.</b>",
                button: "Start Quiz",
            },
            // SECTION 1: The Awakening
            {
                section: "🧠 The Awakening",
                question: 'Have you ever been told that you were circumcised "for your health"... but now wonder if that was ever really true?',
                options: [
                    "I've never questioned it",
                    "I believed it, but now I'm not so sure",
                    "I know now it was a lie",
                    "It makes me angry that I was misled",
                ],
            },
            {
                section: "🧠 The Awakening",
                question: "Did you know that in most developed countries, circumcision is not practiced and is considered unnecessary?",
                options: [
                    "No, I thought everyone did it",
                    "I'd heard that, but didn't give it much thought",
                    "Yes, and I find it disturbing",
                    "Yes, and it changed how I see everything",
                ],
            },
            // ... (rest of the questions)
            {
                section: "OUTRO",
                title: "Thank you for completing the quiz!",
                text: "Your answers have been recorded. You'll soon receive personalized guidance for your masculine restoration journey.<br><br><b>You're brave for looking inside yourself!</b>",
                button: "Finish",
            },
        ];

        let current = 0;
        let answers = [];
        let totalQuestions = quiz.length - 2; // Total questions excluding intro and outro

        function renderQuiz() {
            const container = document.getElementById("quizContainer");
            container.innerHTML = "";

            // Intro
            if (current === 0) {
                const intro = quiz[0];
                container.innerHTML = `
          <div class="intro">
              <h1>${intro.title}</h1>
              <p>${intro.text}</p>
              <button class="nav-btn" onclick="nextStep()">${intro.button}</button>
          </div>
        `;
                return;
            }

            // Outro
            if (current === quiz.length - 1) {
                const outro = quiz[quiz.length - 1];
                container.innerHTML = `
          <div class="outro">
              <h2>${outro.title}</h2>
              <p>${outro.text}</p>
              <form id="quizForm" action="process_quiz.php" method="POST">
                <input type="hidden" name="answers" value='${JSON.stringify(answers)}'>
                <button class="nav-btn" type="submit">${outro.button}</button>
              </form>
          </div>
        `;
                return;
            }

            // Question
            const q = quiz[current];
            const progress = Math.round(((current - 1) / (quiz.length - 3)) * 100);
            const remainingQuestions = totalQuestions - current;

            container.innerHTML = `
        <div class="status-info">Question ${current} of ${totalQuestions} | Remaining: ${remainingQuestions}</div>
        <div class="progress-bar"><div class="progress" style="width:${progress}%;"></div></div>
        <h2>${q.section}</h2>
        <div class="question-section">
            <p style="font-size:1.15em;">${q.question}</p>
        </div>
        <div class="answers" id="answers">
            ${q.options
              .map(
                (opt, i) => `
                  <button class="answer-btn${
                    answers[current] === i ? " selected" : ""
                  }" onclick="selectAnswer(${i})">${opt}</button>
                `
              )
              .join("")}
        </div>
        <div class="nav-btns">
            <button class="nav-btn" onclick="prevStep()" ${
              current === 1 ? "disabled" : ""
            }>Previous</button>
            <button class="nav-btn" onclick="nextStep()" id="nextBtn" ${
              typeof answers[current] === "undefined" ? "disabled" : ""
            }>Next</button>
        </div>
      `;
        }

        function selectAnswer(idx) {
            answers[current] = idx;
            const btns = document.querySelectorAll(".answer-btn");
            btns.forEach((b, i) => b.classList.toggle("selected", i === idx));
            document.getElementById("nextBtn").disabled = false;
        }

        function nextStep() {
            if (current < quiz.length - 1) {
                current++;
                renderQuiz();
            }
        }

        function prevStep() {
            if (current > 0) {
                current--;
                renderQuiz();
            }
        }

        window.onload = renderQuiz;
    </script>
</body>

</html>