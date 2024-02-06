import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Shimmer from './Shimmer';

function Movies() {
  const [posts, setPosts] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(5);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchMovies = async () => {
      setLoading(true);
      try {
        const response = await fetch('/wp-json/wp/v2/movie?order=asc&per_page=10');
        const data = await response.json();

        // Fetch additional data for featured images
        const postsWithImagesAndTaxonomy = await Promise.all(
          data.map(async (post) => {
            const imageResponse = await fetch(`/wp-json/wp/v2/media/${post.featured_media}`);
            const imageData = await imageResponse.json();

            // Fetch taxonomy data
            const taxonomyResponse = await fetch(`/wp-json/wp/v2/genre/${post.genre[0]}`);
            const taxonomyData = await taxonomyResponse.json();
            return {
              ...post,
              featuredImage: imageData,
              taxonomy: taxonomyData,
            };
          })
        );

        setPosts(postsWithImagesAndTaxonomy);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching movies:', error);
        setLoading(false);
      }
    };
    fetchMovies();
  }, []);

  const totalPages = Math.ceil(posts.length / itemsPerPage);
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const currentItems = posts.slice(startIndex, endIndex);
  const nextPage = () => {
    setCurrentPage(prevPage => prevPage + 1);
  };
 const prevPage = () => {
    setCurrentPage(prevPage => prevPage - 1);
  };

  return (
    <>
    {loading && <Shimmer count={itemsPerPage}/>}
    <div className="post-type-movies">
        <div className="movie-listings">
            {currentItems && currentItems.map((post, index) => (
                <div key={index} className="movie-listing">
                <img
                    src={post.featuredImage.media_details.sizes.movie_card.source_url}
                    alt={post.title.rendered}
                />
                <div class="movie-body">
                    <h2 dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
                    <h5>Genre:  <i>{post.taxonomy.name}</i></h5>
                    <p dangerouslySetInnerHTML={{ __html: post.content.rendered }} />
                </div>
                <div class="movie-footer">
                    <Link 
                        key={index}
                        to={{ 
                            pathname: `/movie/${post.slug}`,
                            state: { post: post } 
                        }}
                        className="movie-button">
                        {'View Details'}
                    </Link>
                </div>
                </div>
            ))}
        </div>
        <div className="pagination">
        {currentPage > 1 && <a className="prevButton" onClick={prevPage}>Previous</a>}
        {Array.from({ length: totalPages }, (_, i) => i + 1).map((pageNumber) => (
          <a
            key={pageNumber}
            onClick={() => setCurrentPage(pageNumber)}
            className={pageNumber === currentPage ? "active" : ""}
          >
            {pageNumber}
          </a>
        ))}
        {currentPage < totalPages && <a className="nextButton" onClick={nextPage}>Next</a>}
      </div>
    </div>
    </>
  );
}

export default Movies;