# 🚀 Modern Portfolio Homepage

A stunning, responsive portfolio homepage built with **svelte** and **Tailwind CSS** to showcase projects with modern design aesthetics. Features glassmorphism effects, smooth animations, and full responsiveness across all devices.

![Portfolio Preview](https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop)

## ✨ Features

### 🎨 Modern Design

- **Glassmorphism UI** with backdrop blur effects
- **Gradient backgrounds** with vibrant color schemes
- **Smooth hover animations** and transitions
- **3D transform effects** on project cards
- **Responsive grid layout** that adapts to all screen sizes

### 📱 Responsive Design

- **Mobile-first approach** with Tailwind CSS
- **Flexible grid system**: 1 column on mobile, 2 on tablet, 3 on desktop
- **Optimized typography** for different screen sizes
- **Touch-friendly interactions** for mobile devices

### 🛠 Technical Features

- **React Hooks** (useState, useEffect) for state management
- **Component-based architecture** for maintainability
- **Lazy loading images** for better performance
- **WordPress REST API ready** for dynamic content
- **Clean, semantic HTML** structure

## 🚀 Live Demo

[GitHub Repository](https://github.com/Pgh2005/Portfolio-Builder)

## 📋 Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [WordPress Integration](#wordpress-integration)
- [Customization](#customization)
- [Technologies Used](#technologies-used)
- [Contributing](#contributing)
- [License](#license)

## 🔧 Installation

### Prerequisites

- Node.js (v14.0.0 or higher)
- npm or yarn package manager

### Steps

1. **Clone the repository**

```bash
git clone https://github.com/Pgh2005/Portfolio-Builder.git
cd portfolio-builder
```

2. **Install dependencies**

```bash
npm install
# or
yarn install
```

3. **Start the development server**

```bash
npm start
# or
yarn start
```

4. **Open your browser**
   Navigate to `http://localhost:3000` to view the portfolio

## 🎯 Usage

### Basic Usage

The portfolio displays projects in a responsive grid layout. Each project card includes:

- **Project title**
- **Description** (truncated to 200 characters)
- **Project image**
- **Technology tags**

### Demo Data

The component comes with 6 sample projects including:

- E-Commerce Platform
- AI Dashboard Analytics
- Mobile Fitness App
- Task Management System
- Real Estate Platform
- Learning Management System

## 📁 Project Structure

```
portfolio/
├── src/
│   ├── components/
│   │   └── Portfolio.jsx          # Main portfolio component
│   ├── App.js                     # App component
│   ├── index.js                   # Entry point
│   └── index.css                  # Global styles
├── public/
│   ├── index.html                 # HTML template
│   └── favicon.ico                # Favicon
├── package.json                   # Dependencies and scripts
└── README.md                      # This file
```

## 🔗 WordPress Integration

### Setting Up WordPress REST API

1. **Install WordPress** with REST API enabled
2. **Create custom post type** for projects (optional)
3. **Add custom fields** using Advanced Custom Fields (ACF) plugin:
   - `project_url` - Project URL
   - `technologies` - Comma-separated technologies
   - `project_image` - Project image URL

### Expected Data Structure

```json
{
  "id": 1,
  "title": {
    "rendered": "Project Title"
  },
  "content": {
    "rendered": "Project description content..."
  },
  "acf": {
    "project_url": "https://project-url.com",
    "technologies": "React, Node.js, MongoDB",
    "project_image": "https://image-url.com/image.jpg"
  }
}
```

### Fetching Data from WordPress

Replace the demo data with a fetch call to your WordPress REST API:

```javascript
useEffect(() => {
  const fetchProjects = async () => {
    try {
      const response = await fetch("https://yoursite.com/wp-json/wp/v2/posts");
      const data = await response.json();
      setProjects(data);
    } catch (error) {
      console.error("Error fetching projects:", error);
    }
  };

  fetchProjects();
}, []);
```

## 🎨 Customization

### Changing Colors

The portfolio uses Tailwind CSS classes for styling. You can customize colors by modifying the classes:

```jsx
// Current gradient
className = "bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400";

// Change to blue gradient
className = "bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600";
```

### Modifying Animations

Animations are controlled by Tailwind CSS classes:

```jsx
// Current hover effect
className = "hover:-translate-y-2 hover:scale-105 transition-all duration-500";

// Modify animation
className = "hover:-translate-y-4 hover:scale-110 transition-all duration-300";
```

### Adding New Project Fields

To add new fields to project cards:

1. **Update the `extractProjectData` function:**

```javascript
const extractProjectData = (project) => {
  return {
    title: project.title?.rendered || "Untitled Project",
    description:
      project.content?.rendered?.replace(/<[^>]*>/g, "").substring(0, 200) +
      "...",
    url: project.acf?.project_url || "#",
    technologies: project.acf?.technologies || "Not specified",
    image: project.acf?.project_image || "default-image.jpg",
    // Add new fields here
    github: project.acf?.github_url || "",
    date: project.acf?.project_date || "",
  };
};
```

2. **Update the JSX to display the new fields:**

```jsx
{
  data.github && (
    <a href={data.github} className="text-blue-500 hover:underline">
      View on GitHub
    </a>
  );
}
```

## 🛠 Technologies Used

### Frontend

- **React** (18.0+) - JavaScript library for building user interfaces
- **Tailwind CSS** - Utility-first CSS framework
- **JavaScript ES6+** - Modern JavaScript features

### Development Tools

- **Create React App** - React application setup
- **npm/yarn** - Package management
- **ESLint** - Code linting
- **Prettier** - Code formatting

### APIs

- **WordPress REST API** - For dynamic content management
- **Advanced Custom Fields (ACF)** - WordPress plugin for custom fields

## 🎯 Key Code Explanations

### State Management

```javascript
const [projects, setProjects] = useState([]);
```

- Uses React's `useState` hook to manage project data
- `projects` holds the current project array
- `setProjects` updates the state when data changes

### Data Processing

```javascript
const extractProjectData = (project) => {
  return {
    title: project.title?.rendered || "Untitled Project",
    description:
      project.content?.rendered?.replace(/<[^>]*>/g, "").substring(0, 200) +
      "...",
    // ... other fields
  };
};
```

- Extracts and formats data from WordPress API response
- Uses optional chaining (`?.`) to handle missing data
- Strips HTML tags from content and truncates description
- Provides fallback values for missing fields

### Component Lifecycle

```javascript
useEffect(() => {
  setProjects(demoProjects);
}, []);
```

- `useEffect` runs after component mounts
- Sets initial project data
- Empty dependency array `[]` means it runs only once

### Responsive Grid

```jsx
<div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
```

- Uses CSS Grid with Tailwind classes
- `grid-cols-1` - 1 column on mobile
- `md:grid-cols-2` - 2 columns on medium screens (768px+)
- `xl:grid-cols-3` - 3 columns on extra large screens (1280px+)

### Hover Effects

```jsx
className =
  "transform hover:-translate-y-2 hover:scale-105 transition-all duration-500";
```

- `transform` enables CSS transforms
- `hover:-translate-y-2` moves element up 8px on hover
- `hover:scale-105` scales element to 105% on hover
- `transition-all duration-500` smooth 500ms transition

## 🚀 Performance Optimizations

### Image Loading

```jsx
<img loading="lazy" />
```

- Implements lazy loading for images
- Images load only when they come into viewport
- Reduces initial page load time

### Efficient Re-renders

```javascript
useEffect(() => {
  setProjects(demoProjects);
}, []); // Empty dependency array
```

- Effect runs only once after mount
- Prevents unnecessary re-renders
- Optimizes component performance

## 📱 Responsive Design Breakpoints

```css
/* Mobile First (default) */
grid-cols-1         /* 1 column */

/* Tablet (768px and up) */
md:grid-cols-2      /* 2 columns */

/* Desktop (1280px and up) */
xl:grid-cols-3      /* 3 columns */
```

## 🎨 Design System

### Color Palette

- **Primary**: Indigo to Purple gradient
- **Secondary**: Pink accents
- **Text**: Gray-800 for headings, Gray-600 for body
- **Background**: White with opacity for glassmorphism

### Typography

- **Headings**: Bold (font-bold)
- **Body**: Regular weight
- **Tags**: Medium weight (font-medium)

### Spacing

- **Cards**: 2rem padding (p-8)
- **Grid gaps**: 2rem (gap-8)
- **Element margins**: 1rem to 1.5rem

## 🔄 Future Enhancements

### Planned Features

- [ ] **Dark mode toggle** with theme switching
- [ ] **Search and filter** functionality
- [ ] **Project categories** and tags
- [ ] **Contact form** integration
- [ ] **Blog section** for articles
- [ ] **SEO optimization** with meta tags
- [ ] **Analytics integration** (Google Analytics)
- [ ] **Performance monitoring** (Web Vitals)

### Technical Improvements

- [ ] **TypeScript** migration for better type safety
- [ ] **Testing** with Jest and React Testing Library
- [ ] **CI/CD pipeline** with GitHub Actions
- [ ] **PWA features** for offline functionality
- [ ] **Internationalization** (i18n) support

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Commit your changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```
4. **Push to the branch**
   ```bash
   git push origin feature/amazing-feature
   ```
5. **Open a Pull Request**

### Development Guidelines

- Follow the existing code style
- Add comments for complex logic
- Test your changes on different screen sizes
- Update documentation if needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Tailwind CSS** for the amazing utility-first framework
- **Unsplash** for the beautiful placeholder images
- **React Team** for the excellent documentation
- **WordPress** for the flexible REST API

## 📞 Contact

**Your Name** - [your.email@example.com](mailto:your.email@example.com)

**Project Link** - [https://github.com/yourusername/portfolio](https://github.com/yourusername/portfolio)

**LinkedIn** - [https://linkedin.com/in/yourprofile](https://linkedin.com/in/yourprofile)

---

⭐ **Star this repository** if you found it helpful!
