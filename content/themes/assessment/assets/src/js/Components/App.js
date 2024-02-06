/**
 * App component.
 * @returns {JSXElement}
 */

import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Header from './Header';
import Movies from './Movies';
import SingleMovie from './SingleMovie';
import SinglePost from './SinglePost';
import SinglePage from './SinglePage';
import Footer from './Footer';

const App = () => {
    return (
        <div className="assessment-app">
            <Router>
                <Header />
                <Routes>
                    <Route path="/" element={<Movies />} />
                    <Route path="/movies" element={<Movies />} />
                    <Route path="/movie/:movieSlug" element={<SingleMovie />} />
                    <Route path="/single-post" element={<SinglePost />} />
                    <Route path="/single-page" element={<SinglePage />} />
                </Routes>
                <Footer />
            </Router>
        </div>
    )
}

export default App;