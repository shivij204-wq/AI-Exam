@extends('layout.app')

@section('title', 'Generated Questions')

@section('content')

<div class="container py-5">

    <!-- Page Heading -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">Generated Questions</h2>
        <p class="text-muted">
            Your AI-generated practice questions are ready!
        </p>
    </div>

    <!-- Exam Details -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4 mb-2">
                    <strong>Exam:</strong>
                    <p>{{ $exam }}</p>
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Subject:</strong>
                    <p>{{ $subject }}</p>
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Topic:</strong>
                    <p>{{ $topic }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions -->
    @foreach($questions as $index => $question)

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">
                Question {{ $index + 1 }}
            </div>

            <div class="card-body">

                <h5 class="fw-bold mb-3">
                    {{ $question['question'] }}
                </h5>

                <!-- Options -->
                @foreach($question['options'] as $key => $option)

                    <div class="border rounded p-3 mb-2">
                        <strong>{{ chr(65 + $key) }}.</strong>
                        {{ $option }}
                    </div>

                @endforeach

                <!-- Answer and Explanation -->
                <details class="mt-3">
                    <summary class="btn btn-outline-success">
                        Show Answer & Explanation
                    </summary>

                    <div class="alert alert-success mt-3 mb-0">

                        <p>
                            <strong>Correct Answer:</strong>
                            {{ $question['correct_answer'] }}
                        </p>

                        <p class="mb-0">
                            <strong>Explanation:</strong>
                            {{ $question['explanation'] }}
                        </p>

                    </div>
                </details>

            </div>
        </div>

    @endforeach

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}"class="btn btn-primary">Generate More Questions</a>
    </div>

</div>

@endsection