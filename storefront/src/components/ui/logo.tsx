import Image from 'next/image';
import Link from '@components/ui/link';
import cn from 'classnames';
import { siteSettings } from '@settings/site.settings';
import { ROUTES } from '@lib/routes';

const Logo: React.FC<React.AnchorHTMLAttributes<{}>> = ({
  className,
  ...props
}) => {
  return (
    <Link
      href={ROUTES.HOME}
      className={cn('inline-flex focus:outline-none shrink-0', className)}
      {...props}
    >
      <Image
        src={siteSettings.logo.url}
        alt={siteSettings.logo.alt}
        height={siteSettings.logo.height}
        width={siteSettings.logo.width}
        loading="eager"
        sizes="(max-width: 768px) 100vw"
      />
    </Link>
  );
};

export default Logo;
