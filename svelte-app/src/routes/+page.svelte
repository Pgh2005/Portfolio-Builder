<script lang="ts">
	export let data;
	const projects = data.projects;

	let projectImages: Record<number, string> = {};

	import { onMount } from 'svelte';

	async function getImg(id: number) {
		try {
			const res_img = await fetch(`http://localhost/portfolio-builder/wp-json/wp/v2/media/${id}`);
			const pro_img = await res_img.json();
			return pro_img.guid.rendered;
		} catch (e) {
			return 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&h=300&fit=crop';
		}
	}

	onMount(async () => {
		const imgMap: Record<number, string> = {};
		for (const project of projects) {
			const id = project.acf?.images;
			if (id) {
				imgMap[project.id] = await getImg(id);
			} else {
				imgMap[project.id] =
					'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&h=300&fit=crop';
			}
		}
		projectImages = imgMap;
	});

	// Header functionality
	let isMenuOpen = false;
	let isScrolled = false;

	function toggleMenu() {
		isMenuOpen = !isMenuOpen;
	}

	function closeMenu() {
		isMenuOpen = false;
	}

	// Handle scroll effect
	onMount(() => {
		const handleScroll = () => {
			isScrolled = window.scrollY > 50;
		};

		window.addEventListener('scroll', handleScroll);
		return () => window.removeEventListener('scroll', handleScroll);
	});

	// --------------------------
	// home page section
	let typedText = '';
	let currentRoleIndex = 0;
	let currentCharIndex = 0;
	let isDeleting = false;
	let showCursor = true;

	let roles: string[] = [];
	$: roles =
		lang === 'FA'
			? ['توسعه‌دهنده فول‌استک', 'طراح UI/UX', 'حل‌کننده مسئله', 'تفکر خلاق']
			: ['Full Stack Developer', 'UI/UX Designer', 'Problem Solver', 'Creative Thinker'];

	onMount(() => {
		// Typing animation
		const typeSpeed = 100;
		const deleteSpeed = 50;
		const pauseTime = 2000;

		function typeAnimation() {
			const currentRole = roles[currentRoleIndex];

			if (!isDeleting) {
				if (currentCharIndex < currentRole.length) {
					typedText = currentRole.slice(0, currentCharIndex + 1);
					currentCharIndex++;
					setTimeout(typeAnimation, typeSpeed);
				} else {
					setTimeout(() => {
						isDeleting = true;
						typeAnimation();
					}, pauseTime);
				}
			} else {
				if (currentCharIndex > 0) {
					typedText = currentRole.slice(0, currentCharIndex - 1);
					currentCharIndex--;
					setTimeout(typeAnimation, deleteSpeed);
				} else {
					isDeleting = false;
					currentRoleIndex = (currentRoleIndex + 1) % roles.length;
					setTimeout(typeAnimation, typeSpeed);
				}
			}
		}

		typeAnimation();

		// Cursor blinking
		const cursorInterval = setInterval(() => {
			showCursor = !showCursor;
		}, 500);

		return () => clearInterval(cursorInterval);
	});

	// navigate to footer
	function scrollToExplor() {
		const el = document.getElementById('projects');
		if (el) el.scrollIntoView({ behavior: 'smooth' });
	}

	let lang: 'EN' | 'FA' = 'EN';

	interface Translation {
		home: string;
		about: string;
		projects: string;
		skills: string;
		contact: string;
		greeting: string;
		hireMe: string;
		viewWork: string;
		getInTouch: string;
		description: string;
		projectsStat: string;
		yearsStat: string;
		satisfactionStat: string;
		scroll: string;
	}

	const translations: Record<'EN' | 'FA', Translation> = {
		EN: {
			home: 'Home',
			about: 'About',
			projects: 'Projects',
			skills: 'Skills',
			contact: 'Contact',
			greeting: "Hello, I'm",
			hireMe: 'Hire Me',
			viewWork: 'View My Work',
			getInTouch: 'Get In Touch',
			description:
				'I create beautiful, functional, and user-centered digital experiences. Passionate about turning ideas into reality through clean code and innovative design.',
			projectsStat: 'Projects',
			yearsStat: 'Years Experience',
			satisfactionStat: 'Client Satisfaction',
			scroll: 'Scroll to explore'
		},
		FA: {
			home: 'خانه',
			about: 'درباره',
			projects: 'پروژه‌ها',
			skills: 'مهارت‌ها',
			contact: 'تماس',
			greeting: 'سلام، من',
			hireMe: 'استخدام من',
			viewWork: 'نمونه کارها',
			getInTouch: 'ارتباط با من',
			description:
				'من تجربه‌های دیجیتال زیبا، کاربردی و کاربرمحور خلق می‌کنم. مشتاق تبدیل ایده‌ها به واقعیت با کدنویسی تمیز و طراحی نوآورانه هستم.',
			projectsStat: 'پروژه',
			yearsStat: 'سال تجربه',
			satisfactionStat: 'رضایت مشتری',
			scroll: 'برای مشاهده بیشتر اسکرول کنید'
		}
	};

	// Reactive statement to update document's dir attribute based on lang
	$: {
		if (typeof window !== 'undefined') {
			document.documentElement.dir = lang === 'FA' ? 'rtl' : 'ltr';
		}
	}

	$: isRtl = lang === 'FA';
</script>

<body class:rtl-mode={isRtl}>
	<div></div>
</body>

<!-- Header section -->
<header class="header ltr-header" class:scrolled={isScrolled}>
	<nav class="nav-container">
		<!-- Logo/Brand -->
		<div class="logo">
			<a href="/" class="logo-link">
				<span class="logo-text">Portfolio</span>
				<span class="logo-dot">.</span>
			</a>
		</div>

		<!-- Desktop Navigation -->
		<ul class="nav-links">
			<li><a href="#home" class="nav-link">{translations[lang].home}</a></li>
			<li><a href="#about" class="nav-link">{translations[lang].about}</a></li>
			<li><a href="#projects" class="nav-link">{translations[lang].projects}</a></li>
			<li><a href="#skills" class="nav-link">{translations[lang].skills}</a></li>
			<li><a href="#contact" class="nav-link">{translations[lang].contact}</a></li>
		</ul>

		<!-- CTA Button -->
		<div class="nav-cta">
			<button
				class="border-0 lang-switch-btn"
				on:click={() => (lang = lang === 'EN' ? 'FA' : 'EN')}
			>
				<i class="bi bi-translate me-2" style="font-size:1.2rem;"></i>
				{lang === 'EN' ? 'EN' : 'FA'}
			</button>
		</div>

		<!-- Mobile Menu Button -->
		<button class="mobile-menu-btn" on:click={toggleMenu} aria-label="Toggle menu">
			{#if isMenuOpen}
				<i class="bi bi-x" style="font-size: 2rem;color: white"></i>
			{:else}
				<i class="bi bi-list" style="font-size: 2rem;color: white"></i>
			{/if}
		</button>
	</nav>

	<!-- Mobile Navigation -->
	<div class="mobile-nav" class:active={isMenuOpen}>
		<div class="mobile-nav-content">
			<ul class="mobile-nav-links">
				<li><a href="#home" class="mobile-nav-link" on:click={closeMenu}>Home</a></li>
				<li><a href="#about" class="mobile-nav-link" on:click={closeMenu}>About</a></li>
				<li><a href="#projects" class="mobile-nav-link" on:click={closeMenu}>Projects</a></li>
				<li><a href="#skills" class="mobile-nav-link" on:click={closeMenu}>Skills</a></li>
				<li><a href="#contact" class="mobile-nav-link" on:click={closeMenu}>Contact</a></li>
			</ul>
			<div class="mobile-nav-cta">
				<a href="#contact" class="mobile-cta-button" on:click={closeMenu}>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
						<path
							d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
						/>
					</svg>
					Hire Me
				</a>
			</div>
			<div class="mobile-social-links">
				<a
					href="https://github.com"
					class="mobile-social-link"
					aria-label="GitHub"
					target="_blank"
					rel="noopener noreferrer"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path
							d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
						/>
					</svg>
				</a>
				<a
					href="https://linkedin.com"
					class="mobile-social-link"
					aria-label="LinkedIn"
					target="_blank"
					rel="noopener noreferrer"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path
							d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
						/>
					</svg>
				</a>
				<a
					href="https://twitter.com"
					class="mobile-social-link"
					aria-label="Twitter"
					target="_blank"
					rel="noopener noreferrer"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
						<path
							d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
						/>
					</svg>
				</a>
			</div>
		</div>
	</div>

	<!-- Mobile Menu Overlay -->
	{#if isMenuOpen}
		<!-- svelte-ignore a11y_click_events_have_key_events -->
		<!-- svelte-ignore a11y_no_static_element_interactions -->
		<div class="mobile-nav-overlay" on:click={closeMenu}></div>
	{/if}
</header>

<!-- home section -->
<section id="home" class="home-section">
	<div class="home-container">
		<!-- Main Hero Content -->
		<div class="hero-content">
			<div class="hero-text">
				<div class="greeting">
					<span class="greeting-text">{translations[lang].greeting}</span>
					<div class="greeting-animation">
						<span class="wave">👋</span>
					</div>
				</div>

				<h1 class="hero-title">
					<span class="name-highlight" class:farsi-font={lang === 'FA'}
						>{lang === 'FA' ? 'پارسا دولوپر' : 'Parsa Developer'}</span
					>
					<br />
					<span class="role-text" class:farsi-font={lang === 'FA'}>
						{typedText}<span class="cursor" class:visible={showCursor}>|</span>
					</span>
				</h1>

				<p class="hero-description">
					{translations[lang].description}
				</p>

				<div class="hero-stats">
					<div class="stat-item">
						<span class="stat-number">50+</span>
						<span class="stat-label">{translations[lang].projectsStat}</span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">3+</span>
						<span class="stat-label">{translations[lang].yearsStat}</span>
					</div>
					<div class="stat-divider"></div>
					<div class="stat-item">
						<span class="stat-number">100%</span>
						<span class="stat-label">{translations[lang].satisfactionStat}</span>
					</div>
				</div>

				<div class="hero-actions">
					<a href="#projects" class="cta-primary">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path d="M8 5v14l11-7z" />
						</svg>
						{translations[lang].viewWork}
					</a>
					<a href="#footer" class="cta-secondary">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
							<path
								d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
							/>
						</svg>
						{translations[lang].getInTouch}
					</a>
				</div>
			</div>

			<!-- Hero Visual -->
			<div class="hero-visual">
				<div class="profile-container">
					<div class="profile-ring ring-1"></div>
					<div class="profile-ring ring-2"></div>
					<div class="profile-ring ring-3"></div>
					<div class="profile-image">
						<img
							src="https://i.pinimg.com/736x/89/1c/e8/891ce88cac43afadd7da91fb409a4cb3.jpg"
							alt="Profile"
						/>
					</div>

					<!-- Tech Icons -->
					<div class="tech-icons">
						<div class="tech-icon tech-icon-1">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path
									d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
								/>
							</svg>
						</div>
						<div class="tech-icon tech-icon-2">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path
									d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
								/>
							</svg>
						</div>
						<div class="tech-icon tech-icon-3">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
								<path
									d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0L19.2 12l-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"
								/>
							</svg>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Scroll Indicator -->
		<!-- svelte-ignore a11y_no_static_element_interactions -->
		<!-- svelte-ignore a11y_click_events_have_key_events -->
		<div class="scroll-indicator" on:click={scrollToExplor}>
			<div class="scroll-text">{translations[lang].scroll}</div>
			<div class="scroll-arrow">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
				</svg>
			</div>
		</div>
	</div>
</section>

<div class="container" id="projects">
	<div class="projects-grid">
		{#each projects as project}
			{#if lang === 'FA' && project.class_list[5] == 'category-fa'}
				<div class="project-card">
					<img class="project-image" src={projectImages[project.id]} alt=" " />
					<div class="project-content">
						<div class="project-title-box">
							<h3 class="project-title">{project.title.rendered}</h3>
						</div>
						<div class="project-description-box">
							<p class="project-description">{project.acf.project_description}</p>
						</div>
						<div class="technologies">
							{#each project.acf.technologies_used.split(',') as tech}
								<span class="tech-tag">{tech}</span>
							{/each}
						</div>
						<a href={project.acf.url} class="project-link" target="_blank" rel="noopener noreferrer"
							>مشاهده پروژه</a
						>
					</div>
				</div>
			{:else if lang === 'EN' && project.class_list[5] == 'category-en'}
				<div class="project-card">
					<img class="project-image" src={projectImages[project.id]} alt=" " />
					<div class="project-content">
						<div class="project-title-box">
							<h3 class="project-title">{project.title.rendered}</h3>
						</div>
						<div class="project-description-box">
							<p class="project-description">{project.acf.project_description}</p>
						</div>
						<div class="technologies">
							{#each project.acf.technologies_used.split(',') as tech}
								<span class="tech-tag">{tech}</span>
							{/each}
						</div>
						<a href={project.acf.url} class="project-link" target="_blank" rel="noopener noreferrer"
							>View Project</a
						>
					</div>
				</div>
			{/if}
		{/each}
	</div>
</div>

<footer class="footer" id="footer">
	<div class="footer-container">
		<div class="footer-content">
			<!-- About Section -->
			<div class="footer-section">
				<div class="project-title-box">
					<h4 class="footer-title">{lang === 'FA' ? 'درباره' : 'About'}</h4>
				</div>
				<p class="footer-text">
					{lang === 'FA'
						? 'توسعه‌دهنده‌ای پرشور که با فناوری‌های روز تجربه‌های دیجیتال نوآورانه می‌سازد. همیشه در حال یادگیری و ساختن.'
						: 'Passionate developer crafting innovative digital experiences with cutting-edge technologies. Always learning, always building.'}
				</p>
				<div class="social-links">
					<a
						href="https://github.com"
						class="social-link"
						aria-label="GitHub"
						target="_blank"
						rel="noopener noreferrer"
					>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path
								d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
							/>
						</svg>
					</a>
					<a
						href="https://linkedin.com"
						class="social-link"
						aria-label="LinkedIn"
						target="_blank"
						rel="noopener noreferrer"
					>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path
								d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"
							/>
						</svg>
					</a>
					<a
						href="https://twitter.com"
						class="social-link"
						aria-label="Twitter"
						target="_blank"
						rel="noopener noreferrer"
					>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path
								d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"
							/>
						</svg>
					</a>
					<a href="mailto:your.email@example.com" class="social-link" aria-label="Email">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
							<path
								d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
							/>
						</svg>
					</a>
				</div>
			</div>

			<!-- Quick Links -->
			<div class="footer-section">
				<h4 class="footer-title">{lang === 'FA' ? 'لینک‌های سریع' : 'Quick Links'}</h4>
				<ul class="footer-links">
					<li><a href="#home" class="footer-link">{lang === 'FA' ? 'خانه' : 'Home'}</a></li>
					<li>
						<a href="#projects" class="footer-link">{lang === 'FA' ? 'پروژه‌ها' : 'Projects'}</a>
					</li>
					<li><a href="#about" class="footer-link">{lang === 'FA' ? 'درباره' : 'About'}</a></li>
					<li><a href="#contact" class="footer-link">{lang === 'FA' ? 'تماس' : 'Contact'}</a></li>
					<li><a href="#resume" class="footer-link">{lang === 'FA' ? 'رزومه' : 'Resume'}</a></li>
				</ul>
			</div>

			<!-- Technologies -->
			<div class="footer-section">
				<h4 class="footer-title">{lang === 'FA' ? 'تکنولوژی‌ها' : 'Technologies'}</h4>
				<div class="tech-cloud">
					<span class="tech-item">JavaScript</span>
					<span class="tech-item">React</span>
					<span class="tech-item">Svelte</span>
					<span class="tech-item">Node.js</span>
					<span class="tech-item">Python</span>
					<span class="tech-item">TypeScript</span>
				</div>
			</div>

			<!-- Contact Info -->
			<div class="footer-section">
				<h4 class="footer-title">{lang === 'FA' ? 'ارتباط با من' : 'Get In Touch'}</h4>
				<p class="footer-text">
					{lang === 'FA'
						? 'آماده همکاری در پروژه بعدی شما هستم. بیایید با هم چیزی شگفت‌انگیز بسازیم.'
						: "Ready to collaborate on your next project? Let's create something amazing together."}
				</p>
				<a href="mailto:your.email@example.com" class="contact-button">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
						<path
							d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
						/>
					</svg>
					{lang === 'FA' ? 'ارتباط با من' : 'Contact Me'}
				</a>
			</div>
		</div>

		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="footer-bottom-content">
				<p class="copyright">
					© {new Date().getFullYear()}
					{lang === 'FA' ? 'تمام حقوق برای پارسا محفوظ است.' : 'Your Name. All rights reserved.'}
				</p>
				<div class="footer-bottom-links">
					<a href="#privacy" class="footer-bottom-link"
						>{lang === 'FA' ? 'حریم خصوصی' : 'Privacy Policy'}</a
					>
					<a href="#terms" class="footer-bottom-link"
						>{lang === 'FA' ? 'شرایط استفاده' : 'Terms of Service'}</a
					>
				</div>
			</div>
		</div>
	</div>
</footer>

<style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	/* Header Styles */
	.header {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		z-index: 1000;
		background: rgba(255, 255, 255, 0.1);
		backdrop-filter: blur(20px);
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		transition: all 0.3s ease;
		animation: slideDown 0.8s ease-out;
	}

	.header.scrolled {
		background: rgba(255, 255, 255, 0.15);
		backdrop-filter: blur(30px);
		border-bottom: 1px solid rgba(255, 255, 255, 0.2);
		box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
	}

	.nav-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 20px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 80px;
	}

	/* Logo */
	.logo {
		flex-shrink: 0;
	}

	.logo-link {
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 2px;
	}

	.logo-text {
		font-size: 1.8rem;
		font-weight: 700;
		color: white;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
	}

	.logo-dot {
		font-size: 1.8rem;
		font-weight: 700;
		color: #667eea;
		text-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
	}

	/* Desktop Navigation */
	.nav-links {
		display: flex;
		list-style: none;
		gap: 40px;
		margin: 0;
		padding: 0;
	}

	.nav-link {
		color: rgba(255, 255, 255, 0.9);
		text-decoration: none;
		font-weight: 500;
		font-size: 1rem;
		transition: all 0.3s ease;
		position: relative;
		padding: 8px 0;
	}

	.nav-link::after {
		content: '';
		position: absolute;
		bottom: 0;
		left: 0;
		width: 0;
		height: 2px;
		background: #ffffff87;
		transition: width 0.3s ease;
	}

	.nav-link:hover {
		color: white;
		text-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
	}

	.nav-link:hover::after {
		width: 100%;
	}

	/* CTA Button */
	.nav-cta {
		flex-shrink: 0;
	}

	.lang-switch-btn {
		border-radius: 2rem;
		font-weight: 600;
		letter-spacing: 1px;
		padding: 0.5rem 1.2rem;
		display: flex;
		align-items: center;
		background: #667eea;
		color: #fff;
		transition:
			background 0.2s,
			color 0.2s,
			box-shadow 0.2s;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
		cursor: pointer;
	}

	.lang-switch-btn:hover,
	.lang-switch-btn:focus {
		background: #5042be;
		color: #fff;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
	}

	/* Mobile Menu Button */
	.mobile-menu-btn {
		display: none;
		background: none;
		border: none;
		cursor: pointer;
		padding: 8px 10px;
		border-radius: 8px;
		transition: all 0.3s ease;
	}

	.mobile-menu-btn:hover {
		background: rgba(255, 255, 255, 0.1);
	}

	/* Mobile Navigation */
	.mobile-nav {
		position: fixed;
		top: 80px;
		right: -100%;
		width: 320px;
		height: calc(100vh - 80px);
		background: rgba(255, 255, 255, 0.15);
		backdrop-filter: blur(30px);
		border-left: 1px solid rgba(255, 255, 255, 0.2);
		transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
		overflow-y: auto;
	}

	.mobile-nav.active {
		right: 0;
	}

	.mobile-nav-content {
		padding: 40px 30px;
		height: 100%;
		display: flex;
		flex-direction: column;
	}

	.mobile-nav-links {
		list-style: none;
		margin: 0;
		padding: 0;
		flex-grow: 1;
	}

	.mobile-nav-links li {
		margin-bottom: 20px;
	}

	.mobile-nav-link {
		color: rgba(255, 255, 255, 0.9);
		text-decoration: none;
		font-size: 1.1rem;
		font-weight: 500;
		display: block;
		padding: 12px 0;
		transition: all 0.3s ease;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	}

	.mobile-nav-link:hover {
		color: white;
		text-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
		transform: translateX(10px);
	}

	.mobile-nav-cta {
		margin: 30px 0;
	}

	.mobile-cta-button {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		background: linear-gradient(135deg, #667eea, #764ba2);
		color: white;
		text-decoration: none;
		padding: 14px 28px;
		border-radius: 12px;
		font-weight: 600;
		font-size: 1rem;
		transition: all 0.3s ease;
		box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
		width: 100%;
		justify-content: center;
	}

	.mobile-cta-button:hover {
		transform: translateY(-2px);
		box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
	}

	.mobile-social-links {
		display: flex;
		gap: 15px;
		justify-content: center;
		margin-top: 20px;
	}

	.mobile-social-link {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 45px;
		height: 45px;
		background: rgba(255, 255, 255, 0.1);
		border-radius: 12px;
		color: rgba(255, 255, 255, 0.8);
		transition: all 0.3s ease;
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.2);
	}

	.mobile-social-link:hover {
		background: rgba(255, 255, 255, 0.2);
		color: white;
		transform: translateY(-3px);
	}

	.mobile-nav-overlay {
		position: fixed;
		top: 80px;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.5);
		backdrop-filter: blur(5px);
		z-index: -1;
	}

	/* Animations */
	@keyframes slideDown {
		from {
			transform: translateY(-100%);
			opacity: 0;
		}
		to {
			transform: translateY(0);
			opacity: 1;
		}
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		.nav-links,
		.nav-cta {
			display: none;
		}

		.mobile-menu-btn {
			display: block;
		}

		.nav-container {
			height: 70px;
		}

		.mobile-nav {
			top: 70px;
			height: calc(100vh - 70px);
			width: 100%;
		}

		.mobile-nav-overlay {
			top: 70px;
		}

		.logo-text {
			font-size: 1.5rem;
		}

		.logo-dot {
			font-size: 1.5rem;
		}
	}

	@media (max-width: 1024px) {
		.nav-links {
			gap: 30px;
		}
	}

	/* Add padding to body to account for fixed header */
	body {
		padding-top: 80px;
	}

	@media (max-width: 768px) {
		body {
			padding-top: 70px;
		}
	}

	/* home section */
	.home-section {
		min-height: 100vh;
		display: flex;
		align-items: center;
		position: relative;
		overflow: hidden;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		padding-bottom: 50px;
		padding-top: 10px;
	}

	.home-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 0 20px;
		width: 100%;
		position: relative;
		z-index: 2;
	}

	@keyframes float {
		0%,
		100% {
			transform: translateY(0px) rotate(0deg);
		}
		33% {
			transform: translateY(-30px) rotate(120deg);
		}
		66% {
			transform: translateY(30px) rotate(240deg);
		}
	}

	/* Hero Content */
	.hero-content {
		display: grid;
		grid-template-columns: 1fr 1fr;
		align-items: center;
		gap: 80px;
		min-height: 80vh;
	}

	/* Hero Text */
	.hero-text {
		animation: fadeInUp 1s ease-out;
	}

	.greeting {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 20px;
	}

	.greeting-text {
		font-size: 1.2rem;
		color: rgba(255, 255, 255, 0.9);
		font-weight: 500;
	}

	.wave {
		font-size: 1.5rem;
		animation: wave 2s infinite;
		transform-origin: 70% 70%;
	}

	@keyframes wave {
		0%,
		100% {
			transform: rotate(0deg);
		}
		10%,
		30% {
			transform: rotate(14deg);
		}
		20% {
			transform: rotate(-8deg);
		}
		40%,
		60% {
			transform: rotate(14deg);
		}
		50% {
			transform: rotate(-4deg);
		}
		70% {
			transform: rotate(10deg);
		}
		80% {
			transform: rotate(-2deg);
		}
	}

	.hero-title {
		font-size: 4rem;
		font-weight: 800;
		line-height: 1.1;
		margin-bottom: 30px;
	}

	.name-highlight {
		background: linear-gradient(135deg, #ffffff, #f0f0f0);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
		display: block;
		margin-bottom: 10px;
		text-shadow: 0 4px 20px rgba(255, 255, 255, 0.3);
	}

	.role-text {
		background: linear-gradient(135deg, #4160ea, #6b30a6);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
		font-size: 3rem;
		font-weight: 700;
	}

	.cursor {
		opacity: 0;
		transition: opacity 0.1s;
		color: #667eea;
	}

	.cursor.visible {
		opacity: 1;
	}

	.hero-description {
		font-size: 1.3rem;
		line-height: 1.7;
		color: rgba(255, 255, 255, 0.9);
		margin-bottom: 40px;
		max-width: 500px;
	}

	/* Hero Stats */
	.hero-stats {
		display: flex;
		align-items: center;
		gap: 30px;
		margin-bottom: 50px;
		padding: 25px 0;
	}

	.stat-item {
		text-align: center;
	}

	.stat-number {
		display: block;
		font-size: 2.5rem;
		font-weight: 800;
		color: white;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
	}

	.stat-label {
		font-size: 0.9rem;
		color: rgba(255, 255, 255, 0.8);
		font-weight: 500;
		text-transform: uppercase;
		letter-spacing: 1px;
	}

	.stat-divider {
		width: 2px;
		height: 40px;
		background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.3), transparent);
	}

	/* Hero Actions */
	.hero-actions {
		display: flex;
		gap: 20px;
		flex-wrap: wrap;
	}

	.cta-primary {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		background: rgba(255, 255, 255, 0.95);
		color: #667eea;
		text-decoration: none;
		padding: 16px 32px;
		border-radius: 15px;
		font-weight: 700;
		font-size: 1.1rem;
		transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
		backdrop-filter: blur(20px);
	}

	.cta-primary:hover {
		transform: translateY(-5px);
		box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
		background: white;
	}

	.cta-secondary {
		display: inline-flex;
		align-items: center;
		gap: 10px;
		background: rgba(255, 255, 255, 0.1);
		color: white;
		text-decoration: none;
		padding: 16px 32px;
		border-radius: 15px;
		font-weight: 600;
		font-size: 1.1rem;
		transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
		backdrop-filter: blur(20px);
		border: 2px solid rgba(255, 255, 255, 0.2);
	}

	.cta-secondary:hover {
		transform: translateY(-5px);
		background: rgba(255, 255, 255, 0.2);
		border-color: rgba(255, 255, 255, 0.4);
	}

	/* Hero Visual */
	.hero-visual {
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;
	}

	.profile-container {
		position: relative;
		width: 400px;
		height: 400px;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.profile-ring {
		position: absolute;
		border: 2px solid rgba(255, 255, 255, 0.2);
		border-radius: 50%;
		animation: rotate 20s linear infinite;
	}

	.ring-1 {
		width: 380px;
		height: 380px;
		border-top-color: rgba(255, 255, 255, 0.6);
		animation-duration: 20s;
	}

	.ring-2 {
		width: 320px;
		height: 320px;
		border-right-color: rgba(255, 255, 255, 0.4);
		animation-duration: 15s;
		animation-direction: reverse;
	}

	.ring-3 {
		width: 260px;
		height: 260px;
		border-left-color: rgba(255, 255, 255, 0.3);
		animation-duration: 25s;
	}

	@keyframes rotate {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
	}

	.profile-image {
		width: 200px;
		height: 200px;
		border-radius: 50%;
		overflow: hidden;
		border: 4px solid rgba(255, 255, 255, 0.3);
		backdrop-filter: blur(20px);
		position: relative;
		z-index: 2;
	}

	.profile-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	/* Tech Icons */
	.tech-icons {
		position: absolute;
		width: 100%;
		height: 100%;
	}

	.tech-icon {
		position: absolute;
		width: 60px;
		height: 60px;
		background: rgba(255, 255, 255, 0.15);
		backdrop-filter: blur(20px);
		border-radius: 15px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: white;
		border: 1px solid rgba(255, 255, 255, 0.2);
		animation: techFloat 6s ease-in-out infinite;
	}

	.tech-icon-1 {
		top: 10%;
		right: 10%;
		animation-delay: 0s;
	}

	.tech-icon-2 {
		bottom: 20%;
		left: 5%;
		animation-delay: 2s;
	}

	.tech-icon-3 {
		top: 50%;
		right: -10%;
		animation-delay: 4s;
	}

	@keyframes techFloat {
		0%,
		100% {
			transform: translateY(0px);
		}
		50% {
			transform: translateY(-20px);
		}
	}

	/* Scroll Indicator */
	.scroll-indicator {
		position: absolute;
		bottom: -10px;
		left: 50%;
		transform: translateX(-50%);
		text-align: center;
		color: rgba(255, 255, 255, 0.7);
		animation: bounce 2s infinite;
	}

	.scroll-text {
		font-size: 0.9rem;
		margin-bottom: 10px;
		font-weight: 500;
	}

	.scroll-arrow {
		display: flex;
		justify-content: center;
	}

	@keyframes bounce {
		0%,
		20%,
		50%,
		80%,
		100% {
			transform: translateX(-50%) translateY(0);
		}
		40% {
			transform: translateX(-50%) translateY(-10px);
		}
		60% {
			transform: translateX(-50%) translateY(-5px);
		}
	}

	/* Responsive Design */
	@media (max-width: 1024px) {
		.hero-content {
			gap: 50px;
		}

		.hero-title {
			font-size: 3.5rem;
		}

		.profile-container {
			width: 350px;
			height: 350px;
		}
	}

	@media (max-width: 768px) {
		.hero-content {
			grid-template-columns: 1fr;
			gap: 40px;
			text-align: center;
		}

		.hero-title {
			font-size: 2.8rem;
		}

		.role-text {
			font-size: 2.2rem;
		}

		.hero-description {
			font-size: 1.1rem;
		}

		.hero-stats {
			justify-content: center;
			gap: 20px;
		}

		.hero-actions {
			justify-content: center;
		}

		.profile-container {
			width: 280px;
			height: 280px;
		}

		.profile-image {
			width: 150px;
			height: 150px;
		}

		.cta-primary,
		.cta-secondary {
			width: 100%;
			justify-content: center;
			max-width: 250px;
		}
	}

	@media (max-width: 480px) {
		.hero-title {
			font-size: 2.2rem;
		}

		.role-text {
			font-size: 1.8rem;
		}

		.hero-stats {
			flex-direction: column;
			gap: 15px;
		}

		.stat-divider {
			width: 40px;
			height: 2px;
		}
	}

	/* projects section*/
	body {
		font-family:
			'Inter',
			-apple-system,
			BlinkMacSystemFont,
			'Segoe UI',
			Roboto,
			sans-serif;
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		min-height: 100vh;
		color: #333;
		overflow-x: hidden;
	}

	.container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 60px 20px;
	}

	.projects-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
		gap: 40px;
		animation: fadeIn 1s ease-out;
	}

	.project-card {
		background: rgba(255, 255, 255, 0.903);
		border-color: rgba(255, 255, 255, 0.508);

		border-radius: 20px;
		overflow: hidden;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
		transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
		border: 1px solid rgba(255, 255, 255, 0.2);
		backdrop-filter: blur(20px);
		position: relative;
	}

	.project-card:hover {
		transform: translateY(-10px) scale(1.02);
		box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
	}

	.project-image {
		width: 100%;
		height: 250px;
		object-fit: cover;
		transition: transform 0.4s ease;
	}

	.project-card:hover .project-image {
		transform: scale(1.05);
	}

	.project-content {
		padding: 30px;
	}

	.project-title-box {
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.project-title {
		font-size: 1.8rem;
		font-weight: 700;
		margin-bottom: 15px;
		color: #2d3748;
		line-height: 1.3;
		min-height: 75px;
		max-height: 75px;
	}

	.project-description-box {
		display: -webkit-box;
		-webkit-line-clamp: 4;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		margin-bottom: 20px;
	}

	.project-description {
		color: #718096;
		line-height: 1.7;
		margin-bottom: 25px;
		font-size: 1rem;
		/* min-height: 150px;
		max-height: 150px; */
	}

	.technologies {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
		margin-bottom: 25px;
		min-height: 70px;
		max-height: 70px;
	}

	.tech-tag {
		/* background: rgba(239, 239, 239, 0.167); */
		background: rgba(109, 90, 254, 0.455);
		color: white;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 0.85rem;
		font-weight: 500;
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
		transition: transform 0.2s ease;
		height: fit-content;
	}

	.tech-tag:hover {
		transform: translateY(-2px);
	}

	.project-link {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		background: linear-gradient(135deg, #667eea, #764ba2);
		color: white;
		text-decoration: none;
		padding: 12px 24px;
		border-radius: 12px;
		font-weight: 600;
		transition: all 0.3s ease;
		box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
		position: relative;
		bottom: 0px;
	}

	.project-link:hover {
		transform: translateY(-2px);
		box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
	}

	@keyframes fadeInUp {
		from {
			opacity: 0;
			transform: translateY(30px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	@keyframes fadeIn {
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}

	@media (max-width: 768px) {
		.projects-grid {
			grid-template-columns: 1fr;
			gap: 30px;
		}

		.project-content {
			padding: 25px;
		}
	}

	/* Footer Styles */
	.footer {
		background: rgba(255, 255, 255, 0.1);
		backdrop-filter: blur(20px);
		border-top: 1px solid rgba(255, 255, 255, 0.2);
		margin-top: 80px;
	}

	.footer-container {
		max-width: 1400px;
		margin: 0 auto;
		padding: 60px 20px 20px;
	}

	.footer-content {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
		gap: 40px;
		margin-bottom: 40px;
	}

	.footer-section {
		animation: fadeInUp 1s ease-out;
	}

	.footer-title {
		color: white;
		font-size: 1.3rem;
		font-weight: 700;
		margin-bottom: 20px;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
	}

	.footer-text {
		color: rgba(255, 255, 255, 0.8);
		line-height: 1.6;
		margin-bottom: 20px;
		font-size: 0.95rem;
	}

	.social-links {
		display: flex;
		gap: 15px;
	}

	.social-link {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 45px;
		height: 45px;
		background: rgba(255, 255, 255, 0.1);
		border-radius: 12px;
		color: rgba(255, 255, 255, 0.8);
		transition: all 0.3s ease;
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.2);
	}

	.social-link:hover {
		background: rgba(255, 255, 255, 0.2);
		color: white;
		transform: translateY(-3px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
	}

	.footer-links {
		display: flex;
		flex-direction: column;
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.footer-links li {
		margin-bottom: 12px;
	}

	.footer-link {
		color: rgba(255, 255, 255, 0.7);
		text-decoration: none;
		font-size: 0.95rem;
		transition: all 0.3s ease;
		display: inline-block;
	}

	.footer-link:hover {
		color: white;
		transform: translateX(5px);
	}

	.tech-cloud {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.tech-item {
		background: rgba(255, 255, 255, 0.1);
		color: rgba(255, 255, 255, 0.9);
		padding: 6px 12px;
		border-radius: 15px;
		font-size: 0.8rem;
		font-weight: 500;
		backdrop-filter: blur(10px);
		border: 1px solid rgba(255, 255, 255, 0.2);
		transition: all 0.3s ease;
	}

	.tech-item:hover {
		background: rgba(255, 255, 255, 0.2);
		transform: translateY(-2px);
	}

	.contact-button {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		background: linear-gradient(135deg, #667eea, #764ba2);
		color: white;
		text-decoration: none;
		padding: 12px 24px;
		border-radius: 12px;
		font-weight: 600;
		transition: all 0.3s ease;
		box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
		font-size: 0.9rem;
	}

	.contact-button:hover {
		transform: translateY(-2px);
		box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
	}

	.footer-bottom {
		border-top: 1px solid rgba(255, 255, 255, 0.2);
		padding-top: 30px;
	}

	.footer-bottom-content {
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 20px;
	}

	.copyright {
		color: rgba(255, 255, 255, 0.6);
		font-size: 0.9rem;
		margin: 0;
	}

	.footer-bottom-links {
		display: flex;
		gap: 30px;
	}

	.footer-bottom-link {
		color: rgba(255, 255, 255, 0.6);
		text-decoration: none;
		font-size: 0.9rem;
		transition: color 0.3s ease;
	}

	.footer-bottom-link:hover {
		color: rgba(255, 255, 255, 0.9);
	}

	@media (max-width: 768px) {
		.footer-container {
			padding: 40px 20px 20px;
		}

		.footer-content {
			grid-template-columns: 1fr;
			gap: 30px;
		}

		.footer-bottom-content {
			flex-direction: column;
			text-align: center;
		}

		.footer-bottom-links {
			gap: 20px;
		}

		.social-links {
			justify-content: center;
		}

		.footer-links {
			flex-direction: row;
			gap: 15px;
		}
	}

	/* Footer link underline animation */
	.footer-link,
	.footer-bottom-link {
		position: relative;
		transition: color 0.3s;
	}

	/* Social icon hover pulse */
	.social-link,
	.mobile-social-link {
		transition:
			transform 0.2s,
			box-shadow 0.2s;
	}
	.social-link:hover,
	.mobile-social-link:hover {
		transform: scale(1.15) rotate(-8deg);
		box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
	}

	.rtl-mode {
		direction: rtl;
	}

	.farsi-font {
		font-family: 'B titr', 'Vazir', 'IRANSans', Tahoma, Arial, sans-serif;
		letter-spacing: 0;
	}

	.ltr-header,
	.ltr-header * {
		direction: ltr !important;
		text-align: left;
	}
</style>
