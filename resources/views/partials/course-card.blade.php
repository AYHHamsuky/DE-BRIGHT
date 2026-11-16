<article class="card course-card">
    <div class="tags">
        <span class="tag">{{ $course->level }}</span>
        <span class="tag time">{{ $course->duration }}</span>
        @if ($course->code)
            <span class="tag">{{ $course->code }}</span>
        @endif
    </div>
    <h3>{{ $course->name }}</h3>
    @if ($course->summary)
        <p>{{ $course->summary }}</p>
    @endif
</article>
