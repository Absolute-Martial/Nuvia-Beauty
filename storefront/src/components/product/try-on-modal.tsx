import React, { useEffect, useState } from 'react';
import Image from 'next/image';
import Cookies from 'js-cookie';
import Button from '@components/ui/button';
import { useUI } from '@contexts/ui.context';
import { AUTH_CRED } from '@lib/constants';
import {
  IoCloudUploadOutline,
  IoCheckmarkCircleOutline,
  IoHappyOutline,
  IoReloadCircleOutline,
  IoSparklesOutline,
} from 'react-icons/io5';

interface TryOnModalProps {
  productId: string;
}

const HIFI_FALLBACK_MESSAGE =
  'Sorry, we could not generate a high-fidelity try-on for this item right now. Please try another photo or product.';
const SIGN_IN_MESSAGE = 'Please sign in';
const apiBaseURL =
  typeof window === 'undefined'
    ? process.env.INTERNAL_REST_API_ENDPOINT ||
      process.env.NEXT_PUBLIC_REST_API_ENDPOINT ||
      'http://127.0.0.1:8000'
    : '/api-backend';

const TryOnModal: React.FC<TryOnModalProps> = ({ productId }) => {
  const { closeModal } = useUI();
  const [file, setFile] = useState<File | null>(null);
  const [preview, setPreview] = useState<string | null>(null);
  const [status, setStatus] = useState<'idle' | 'uploading' | 'processing' | 'success' | 'error'>('idle');
  const [resultUrl, setResultUrl] = useState<string | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [compare, setCompare] = useState<number>(50);

  useEffect(() => {
    return () => {
      if (preview) {
        URL.revokeObjectURL(preview);
      }
    };
  }, [preview]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const selectedFile = e.target.files?.[0];
    if (!selectedFile) {
      return;
    }

    setFile(selectedFile);
    setPreview(URL.createObjectURL(selectedFile));
    setStatus('idle');
    setResultUrl(null);
    setErrorMessage(null);
    setCompare(50);
  };

  const getHeaders = (): Record<string, string> => {
    const token = Cookies.get(AUTH_CRED);
    if (!token) {
      return { Accept: 'application/json' };
    }

    try {
      return {
        Authorization: `Bearer ${JSON.parse(token).token}`,
        Accept: 'application/json',
      };
    } catch (_error) {
      return { Accept: 'application/json' };
    }
  };

  const pollTask = async (taskId: string) => {
    try {
      const response = await fetch(`${apiBaseURL}/zyro/try-on/task/${taskId}`, {
        headers: getHeaders(),
      });
      const data = await response.json();

      if (response.status === 401) {
        setStatus('error');
        setErrorMessage(SIGN_IN_MESSAGE);
        return;
      }

      if (data.status === 'success') {
        setResultUrl(data.result_url);
        setStatus('success');
        setCompare(50);
        return;
      }

      if (data.status === 'failed' || data.status === 'error') {
        setStatus('error');
        setErrorMessage(HIFI_FALLBACK_MESSAGE);
        return;
      }

      setTimeout(() => {
        void pollTask(taskId);
      }, 3000);
    } catch (error: any) {
      setStatus('error');
      setErrorMessage(error.message || 'Failed to poll task status.');
    }
  };

  const handleTryOn = async () => {
    if (!file || !productId) {
      return;
    }

    setStatus('uploading');
    setErrorMessage(null);
    setResultUrl(null);

    try {
      const formData = new FormData();
      formData.append('photo', file);

      const uploadRes = await fetch(`${apiBaseURL}/zyro/try-on/upload`, {
        method: 'POST',
        headers: getHeaders(),
        body: formData,
      });

      if (!uploadRes.ok) {
        const errorData = await uploadRes.json();
        if (uploadRes.status === 401) {
          throw new Error(SIGN_IN_MESSAGE);
        }
        throw new Error(errorData.message || 'Failed to upload photo');
      }

      const uploadData = await uploadRes.json();
      const photoId = uploadData.photo_id;

      setStatus('processing');
      const taskRes = await fetch(`${apiBaseURL}/zyro/try-on/task`, {
        method: 'POST',
        headers: {
          ...getHeaders(),
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          photo_id: photoId,
          product_id: productId,
        }),
      });

      if (!taskRes.ok) {
        const errorData = await taskRes.json();
        if (taskRes.status === 401) {
          throw new Error(SIGN_IN_MESSAGE);
        }
        throw new Error(errorData.message || 'Failed to start try-on task');
      }

      const taskData = await taskRes.json();
      if (!taskData.task_id || taskData.status === 'failed') {
        throw new Error(HIFI_FALLBACK_MESSAGE);
      }

      void pollTask(taskData.task_id);
    } catch (error: any) {
      setStatus('error');
      setErrorMessage(error.message || HIFI_FALLBACK_MESSAGE);
    }
  };

  const isBusy = status === 'uploading' || status === 'processing';
  const hasResult = status === 'success' && Boolean(resultUrl);
  const beforeImage = preview ?? '/assets/placeholder/products/product-thumbnail.svg';
  const afterImage = resultUrl ?? beforeImage;
  const loadingMessage =
    status === 'uploading'
      ? 'Uploading your photo...'
      : status === 'processing'
        ? 'Creating your happy look...'
        : '';

  return (
    <div className="relative flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
      <button
        onClick={closeModal}
        className="absolute right-4 top-4 z-20 text-gray-500 hover:text-gray-700"
      >
        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div className="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr]">
        <div className="relative min-h-[28rem] bg-neutral-950 p-4 sm:p-6">
          <div className="mb-4 flex items-center justify-between">
            <div>
              <p className="text-xs uppercase tracking-[0.2em] text-white/60">Virtual Try-On</p>
              <h2 className="text-2xl font-semibold text-white sm:text-3xl">Before and after</h2>
            </div>
            <div className="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white">
              <IoHappyOutline className="text-base" />
              Happy look
            </div>
          </div>

          <div className="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0f1115]" style={{ aspectRatio: '4 / 5' }}>
            <div className="absolute inset-0">
              <Image
                src={beforeImage}
                alt="Before try-on"
                fill
                unoptimized={Boolean(preview && preview.startsWith('blob:'))}
                className="object-cover"
                sizes="(max-width: 1024px) 100vw, 60vw"
              />
            </div>

            <div
              className="absolute inset-0 overflow-hidden"
              style={{ clipPath: `inset(0 ${100 - compare}% 0 0)` }}
            >
              <Image
                src={afterImage}
                alt="After try-on"
                fill
                unoptimized={Boolean(resultUrl && resultUrl.startsWith('blob:'))}
                className="object-cover"
                sizes="(max-width: 1024px) 100vw, 60vw"
              />
            </div>

            <div className="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent" />

            <div className="absolute left-4 top-4 rounded-full bg-black/45 px-3 py-1 text-xs font-semibold text-white backdrop-blur-sm">
              Before
            </div>
            <div className="absolute right-4 top-4 rounded-full bg-emerald-500/80 px-3 py-1 text-xs font-semibold text-white backdrop-blur-sm">
              After
            </div>

            <div
              className="absolute top-0 bottom-0 w-px bg-white/90 shadow-[0_0_0_3px_rgba(255,255,255,0.18)]"
              style={{ left: `${compare}%` }}
            >
              <div className="absolute left-1/2 top-1/2 flex h-10 w-10 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-white text-neutral-900 shadow-lg">
                <span className="text-[10px] font-black uppercase tracking-[0.2em]">VS</span>
              </div>
            </div>

            {isBusy && (
              <div className="absolute inset-0 flex flex-col items-center justify-center bg-neutral-950/80 px-6 text-center backdrop-blur-sm">
                <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-white">
                  <IoSparklesOutline className="animate-pulse text-3xl" />
                </div>
                <p className="text-lg font-semibold text-white">{loadingMessage}</p>
                <p className="mt-2 max-w-sm text-sm text-white/70">
                  We are rendering your full-body try-on and preparing the result panel.
                </p>

                <div className="mt-6 w-full max-w-sm space-y-3">
                  <div className="h-2 overflow-hidden rounded-full bg-white/10">
                    <div className="h-full w-2/3 animate-pulse rounded-full bg-white/80" />
                  </div>
                  <div className="grid grid-cols-3 gap-3 text-left text-xs text-white/80">
                    <div className="rounded-xl border border-white/10 bg-white/5 p-3">
                      <IoCloudUploadOutline className="mb-2 text-lg text-white" />
                      Photo
                    </div>
                    <div className="rounded-xl border border-white/10 bg-white/5 p-3">
                      <IoSparklesOutline className="mb-2 text-lg text-white" />
                      Render
                    </div>
                    <div className="rounded-xl border border-white/10 bg-white/5 p-3">
                      <IoHappyOutline className="mb-2 text-lg text-white" />
                      Finish
                    </div>
                  </div>
                </div>
              </div>
            )}

            {!preview && !isBusy && (
              <div className="absolute inset-0 flex items-center justify-center bg-neutral-950/50 text-center text-white">
                <div className="max-w-xs px-6">
                  <IoCloudUploadOutline className="mx-auto mb-3 text-4xl text-white/90" />
                  <p className="text-sm text-white/80">Upload a front-facing full-body photo to begin</p>
                </div>
              </div>
            )}
          </div>

          <div className="mt-4">
            <input
              type="range"
              min={0}
              max={100}
              value={compare}
              onChange={(event) => setCompare(Number(event.target.value))}
              disabled={!hasResult && !preview}
              className="h-2 w-full cursor-pointer appearance-none rounded-full bg-white/10 accent-white disabled:cursor-not-allowed"
            />
            <div className="mt-2 flex items-center justify-between text-xs text-white/60">
              <span>Before</span>
              <span>Drag to compare</span>
              <span>After</span>
            </div>
          </div>
        </div>

        <div className="flex flex-col gap-5 bg-white p-5 sm:p-6">
          <div>
            <p className="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Photo Try-On</p>
            <h3 className="mt-2 text-2xl font-semibold text-heading">Create a styled look</h3>
            <p className="mt-2 text-sm leading-6 text-gray-600">
              Upload a clean, full-body photo and render the product on the body with a before-and-after comparison.
            </p>
          </div>

          <label className="group relative flex min-h-[180px] cursor-pointer items-center justify-center overflow-hidden rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 text-center transition hover:border-gray-400">
            {preview ? (
              <Image
                src={preview}
                alt="Preview"
                fill
                unoptimized={preview.startsWith('blob:')}
                className="object-cover"
                sizes="(max-width: 1024px) 100vw, 40vw"
              />
            ) : (
              <div className="relative z-10">
                <IoCloudUploadOutline className="mx-auto mb-3 text-4xl text-gray-400 group-hover:text-gray-500" />
                <p className="text-sm text-gray-600">Drop or choose a photo</p>
                <p className="mt-1 text-xs text-gray-400">JPEG or PNG, front-facing full body</p>
              </div>
            )}
            <input
              type="file"
              accept="image/jpeg, image/png"
              onChange={handleFileChange}
              className="absolute inset-0 h-full w-full cursor-pointer opacity-0"
            />
          </label>

          <div className="rounded-2xl border border-gray-200 bg-gray-50 p-4">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                {hasResult ? <IoCheckmarkCircleOutline className="text-xl" /> : <IoHappyOutline className="text-xl" />}
              </div>
              <div>
                <p className="text-sm font-semibold text-heading">
                  {hasResult ? 'Happy look ready' : 'Try-on status'}
                </p>
                <p className="text-xs text-gray-500">
                  {hasResult
                    ? 'Use the slider to compare the before and after effect.'
                    : isBusy
                      ? loadingMessage
                      : 'Select a photo and generate the look.'}
                </p>
              </div>
            </div>

            {status === 'error' && (
              <p className="mt-3 rounded-xl bg-red-50 px-3 py-2 text-sm text-red-600">{errorMessage}</p>
            )}
          </div>

          <div className="mt-auto space-y-3">
            <Button
              onClick={handleTryOn}
              disabled={!file || isBusy}
              className="w-full"
              loading={isBusy}
            >
              {hasResult ? 'Generate Another Look' : 'Generate Try-On'}
            </Button>

            {hasResult && resultUrl ? (
              <div className="flex items-center justify-between gap-3">
                <Button
                  onClick={() => {
                    setStatus('idle');
                    setResultUrl(null);
                    setErrorMessage(null);
                    setCompare(50);
                  }}
                  variant="outline"
                  className="flex-1"
                >
                  <IoReloadCircleOutline className="mr-2 text-lg" />
                  Try Another Photo
                </Button>
                <a
                  href={resultUrl}
                  target="_blank"
                  rel="noreferrer"
                  className="text-sm font-medium text-accent hover:underline"
                >
                  Open result
                </a>
              </div>
            ) : null}
          </div>
        </div>
      </div>
    </div>
  );
};

export default TryOnModal;
