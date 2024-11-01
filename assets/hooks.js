import { useState, useEffect } from 'react';

export const useLazyload = () => {
	const [doLoad, setIsShown] = useState(false);
	const [element, lazyload] = useState();

	useEffect(() => {
		if (element) {
			const observer = new IntersectionObserver((entries, observer) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						setIsShown(true);
						observer.unobserve(entry.target);
					}
				})
			});

			observer.observe(element);
		}
	}, [element, setIsShown]);

	return { doLoad, lazyload };
}
