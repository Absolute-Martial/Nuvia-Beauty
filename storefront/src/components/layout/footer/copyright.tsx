import Container from '@components/ui/container';
import Spinner from '@components/ui/loaders/spinner/spinner';
import { useSettings } from '@framework/settings';

const Copyright = () => {
  const { data, isLoading } = useSettings();
  const date = new Date();
  return (
    <div className="border-t border-gray-300 pt-5 pb-16 sm:pb-20 md:pb-5 mb-2 sm:mb-0">
      <Container className="flex flex-col-reverse md:flex-row text-center md:justify-between">
        {isLoading ? (
          <Spinner simple />
        ) : (
          <p className="text-body text-xs md:text-[13px] lg:text-sm leading-6">
            ©{date.getFullYear()} Nuvia Beauty. MIT License.
          </p>
        )}
      </Container>
    </div>
  );
};

export default Copyright;
