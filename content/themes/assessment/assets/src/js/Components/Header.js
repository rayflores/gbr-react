import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { Squash as Hamburger } from 'hamburger-react';

function Header() {
    const [isOpen, setIsOpen] = useState(false);
    const [slug, setSlug] = useState({});
  return (
    <header>
      <nav className="navbar">
        <div className="container">
            <button onClick={() => setIsOpen(!isOpen)}>
                <Hamburger toggled={isOpen} size={20} className={isOpen ? 'open' : '' } />
            </button>
            <ul class={isOpen ? 'mobile' : 'desktop'}>
            <li><NavLink to="/" exact activeClass="active">Home</NavLink></li>
            <li><NavLink to="/movies" activeClass="active">Movies</NavLink></li>
            <li><NavLink to="/single-movie" activeClass="active">Single Movie</NavLink></li>
            <li><NavLink to="/single-post" activeClass="active">Single Post</NavLink></li>
            <li><NavLink to="/single-page" activeClass="active">Single Page</NavLink></li>
            </ul>
        </div>
      </nav>
    </header>
  );
}
export default Header;