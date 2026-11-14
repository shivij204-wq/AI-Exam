@extends('layout.app')

@section('title', 'Home - AI Exam Generator')

@section('content')

<div class="container py-5"><div class="text-center mb-5">
    <h2 class="fw-bold">AI Exam Question Generator</h2>
    <p class="text-muted">
        Generate questions and answers with AI for your exam preparation.
    </p>
</div>

<div class="card shadow border-0 mx-auto p-4"
     style="max-width: 700px; border-radius: 15px;">

    <h4 class="text-center mb-4">Generate Your Questions</h4>

    @if(session('error'))
    <div class="alert alert-danger">
        <strong>Error:</strong> {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Validation Error:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('question.generate') }}" method="POST">

        @csrf

        <!-- Exam Name -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Select Exam</label>

            <select name="exam_name" class="form-select" required>
                <option value="">Choose Exam</option>
                <option value="UPSC">UPSC</option>
                <option value="SSC">SSC</option>
                <option value="Railway">Railway</option>
                <option value="Banking">Banking</option>
                <option value="NEET">NEET</option>
                <option value="JEE">JEE</option>
                 <option value="UPP Computer operator">UPP Computer operator</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Subject -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Select Subject</label>

            <select name="subject" class="form-select" required>
                <option value="">Choose Subject</option>
                <option value="History">History</option>
                <option value="Geography">Geography</option>
                <option value="Polity">Polity</option>
                <option value="Physics">Physics</option>
                <option value="Chemistry">Chemistry</option>
                <option value="Biology">Biology</option>
                <option value="Mathematics">Mathematics</option>
                <option value="English">English</option>
                 <option value="Computer">Computer</option>
                <option value="Reasoning">Reasoning</option>
                <option value="General Knowledge">General Knowledge</option>
            </select>
        </div>

        <!-- Topic -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Enter Topic</label>

            <input type="text"
                   name="topic"
                   class="form-control"
                   placeholder="Example: Mughal Empire"
                   value="{{ old('topic') }}"
                   required>
        </div>

        <!-- Number of Questions -->
        <div class="mb-4">
            <label class="form-label fw-semibold">
                Number of Questions
            </label>

            <select name="count" class="form-select" required>
                <option value="5">5 Questions</option>
                <option value="10">10 Questions</option>
                <option value="20">20 Questions</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
    <button type="submit" class="btn btn-primary btn-lg">
        Generate Questions
    </button>
</div>

</form>
</div>

</div>
@endsection