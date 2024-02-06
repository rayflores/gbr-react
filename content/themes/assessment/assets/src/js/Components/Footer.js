import React from "react";
import { NavLink } from 'react-router-dom';

function Footer() {
    return (
        <footer>
        <div className="footer-wrapper">
            <div className="footer-social">
                <a href="#" target="_blank" rel="noreferrer"><i className="fa fa-facebook"></i></a>
                <a href="#" target="_blank" rel="noreferrer"><i className="fa fa-twitter"></i></a>
                <a href="#" target="_blank" rel="noreferrer"><i className="fa fa-linkedin"></i></a>
                <a href="#" target="_blank" rel="noreferrer"><i className="fa fa-github"></i></a>
            </div>
          <nav class="footer-nav">
            <NavLink to="/" exact activeClass="active">Home</NavLink>
            <NavLink to="/movies" activeClass="active">Movies</NavLink>
            <NavLink to="/single-movie" activeClass="active">Single Movie</NavLink>
            <NavLink to="/single-post" activeClass="active">Single Post</NavLink>
            <NavLink to="/single-page" activeClass="active">Single Page</NavLink>
          </nav>
          <div className="footer-copyright">
            <small>&copy;{(new Date().getFullYear())} <strong>Movie Company</strong>, All Rights Reserved</small>
          </div>
        </div>
      </footer>
    );
}
export default Footer;