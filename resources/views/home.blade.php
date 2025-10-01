<x-layouts.base>
    <!-- Add custom styles -->
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/home/style.css') }}">
    @endpush
    
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="logo text-2xl font-bold text-blue-600">Atlas Roads</div>
        <nav class="space-x-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
            <a href="#contact" class="text-gray-700 hover:text-blue-600">Contact</a>
        </nav>
    </div>
</header>

<main>
    <div class="carousel">
        <div class="list">
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img1.png') }}" class="img">
                <div class="content">
                    <div class="author">Atlas Roads</div>
                    <div class="title">Road Management System</div>
                    <div class="topic">Modern Infrastructure Solutions</div>
                    <div class="des">
                        Streamline your road infrastructure management with our comprehensive dashboard.
                    </div>
                    <div class="buttons">
                        <button>Learn More</button>
                        <button>Get Started</button>
                    </div>
                </div>
            </div>
            
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img2.png') }}" class="img">
                <div class="content">
                    <div class="author">Analytics</div>
                    <div class="title">Real-time Monitoring</div>
                    <div class="topic">Smart Data Analysis</div>
                    <div class="des">
                        Monitor road conditions and traffic patterns in real-time.
                    </div>
                    <div class="buttons">
                        <button>See Features</button>
                        <button>Demo</button>
                    </div>
                </div>
            </div>
            
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img3.png') }}" class="img">
                <div class="content">
                    <div class="author">Maintenance</div>
                    <div class="title">Preventive Care</div>
                    <div class="topic">Infrastructure Health</div>
                    <div class="des">
                        Schedule and track maintenance activities efficiently.
                    </div>
                    <div class="buttons">
                        <button>Learn More</button>
                        <button>Get Started</button>
                    </div>
                </div>
            </div>
            
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img4.png') }}" class="img">
                <div class="content">
                    <div class="author">Safety</div>
                    <div class="title">Public Safety First</div>
                    <div class="topic">Risk Management</div>
                    <div class="des">
                        Ensure road safety with proactive monitoring and quick response.
                    </div>
                    <div class="buttons">
                        <button>See Features</button>
                        <button>Demo</button>
                    </div>
                </div>
            </div>
            
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img5.png') }}" class="img">
                <div class="content">
                    <div class="author">Planning</div>
                    <div class="title">Future-Ready</div>
                    <div class="topic">Smart Planning</div>
                    <div class="des">
                        Plan infrastructure development with data-driven insights.
                    </div>
                    <div class="buttons">
                        <button>Learn More</button>
                        <button>Get Started</button>
                    </div>
                </div>
            </div>
            
            <div class="item">
                <img src="{{ asset('assets/img/home/images/img6.png') }}" class="img">
                <div class="content">
                    <div class="author">Integration</div>
                    <div class="title">Connected Systems</div>
                    <div class="topic">Smart City Integration</div>
                    <div class="des">
                        Seamlessly integrate with other smart city systems.
                    </div>
                    <div class="buttons">
                        <button>See Features</button>
                        <button>Demo</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
            <button id="back">See All  &#8599</button>
        </div>
    </div>
</main>
    @push('scripts')
        <script src="{{ asset('assets/js/home/app.js') }}"></script>
        <script src="{{ asset('assets/js/home/scroll-animation.js') }}"></script>
    @endpush
</x-layouts.base>
