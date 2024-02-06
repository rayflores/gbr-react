import React, { useState, useEffect } from "react";
import { Link, useParams } from "react-router-dom";
import Shimmer from './Shimmer';

function SingleMovie() {
    const { movieSlug } = useParams();
    const [movie, setMovie] = useState({});
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(true);
        fetchPost();
    }, []);

    const fetchPost = async () => {
        const fetchMovie = await fetch(`/wp-json/wp/v2/movie?slug=${movieSlug}`);
        let movie = await fetchMovie.json();
        movie = await fetchMovieImage(movie[0]);
        setMovie(movie);
        setLoading(false);
    };
    const fetchMovieImage = async (movie) => {
        const imageResponse = await fetch(`/wp-json/wp/v2/media/${movie.featured_media}`);
        const imageData = await imageResponse.json();
        return {
            ...movie,
            featuredImage: imageData,
        };
    };


    return (
        <>
            <div className="single-movie">
                {console.log(loading)}
                {movie.featuredImage && 
                <img 
                    src={movie.featuredImage.media_details.sizes.movie_card.source_url} 
                    alt={movie.title && movie.title.rendered}
                />}
                <div className="single-movie-content">
                    {movie.title && <h1 dangerouslySetInnerHTML={{ __html: movie.title.rendered }} />}
                    {movie.content && <p dangerouslySetInnerHTML={{ __html: movie.content.rendered }} />}
                </div>    
            </div>
            {!loading && <Shimmer count={1}/>}
            {!loading && <Link to="/movies" className="backTo">Back to All Movies</Link>}
        </>
    );
}

export default SingleMovie;